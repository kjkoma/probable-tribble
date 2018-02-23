<?php
/**
 * Copyright (c) Japan Computer Services, Inc.
 *
 * Licensed under The MIT License
 *
 * @author    Japan Computer Services, Inc
 * @copyright Copyright (c) Japan Computer Services, Inc. (http://www.japacom.co.jp)
 * @since     1.0.0
 * @version   1.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 *
 * -- Histories --
 * 2017.12.31 R&D 新規作成
 */
namespace App\Controller\Component;

/**
 * 分類（Classifications）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelClassificationsComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['ModelClassTree'];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'Classifications';
        parent::initialize($config);
    }

    /**
     * 指定されたカテゴリで指定された分類を更新する
     *  
     * - - -
     * @param array $categoryId カテゴリID
     * @param array $classifications 分類一覧
     * @return array 保存結果（result:true/false, errors:エラー内容, data: 保存データ）
     */
    public function saveCategory($categoryId, $classifications)
    {
        $result = $this->_result(true, []);
        foreach($classifications as $classification) {
            $classification['category_id'] = $categoryId;
            $data = is_array($classification) ? $classification : $classification->toArray();
            $result = $this->save($data);
            if (!$result['result']) {
                return $result;
            }
        }

        return $result;
    }

    /**
     * 指定された分類階層のdescendant（子孫）の分類IDの分類一覧を取得する
     *  
     * - - -
     * @param array $tree 分類階層一覧
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 分類一覧
     */
    public function descendant($tree, $toArray = false)
    {
        $ids = [];
        foreach($tree as $item) {
            $ids[] = $item['descendant'];
        }

        $query = $this->modelTable->find('sorted');
        if (count($ids) === 0) {
            $query->where([ 'id' => null ]); // 何も取得しない
        } else {
            $query->where([ 'id IN' => $ids ]);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 分類を検索する（配下分類を含む）（kname or name）
     *  
     * - - -
     * @param string $search 検索文字列
     * @param string $classificationId 分類ID（指定分類配下を取得しない場合に指定）
     * @return array 分類一覧（select2用id/textペア）
     */
    public function find2List($search, $classificationId = null)
    {
        $query = $this->modelTable->find('valid');

        if (!is_null($search) && $search !== '' && $search != '*') {
            $query->andWhere(function($exp) use ($search) {
                return $exp->or_([
                    'kname like ' => '%' . $search . '%',
                    'name like ' => '%' . $search . '%',
                ]);
            });
        }

        $descendant = [];
        if (!is_null($classificationId)) {
            $list = $this->ModelClassTree->descendant($classificationId, true);
            foreach($list as $item) {
                array_push($descendant, $item['descendant']);
            }
        }
        if (count($descendant) > 0) {
            $query->where(function($exp) use ($descendant) {
                return $exp->notIn('id', $descendant);
            });
        }
        $list = $query->all();

        $classifications = [];
        foreach($list as $item) {
            array_push($classifications, [
                'id'   => $item['id'],
                'text' => $item['kname']
            ]);
        }

        return $classifications;
    }

    /**
     * 分類を検索する（カテゴリ指定含む）（kname or name / category_id）
     *  
     * - - -
     * @param string $search 検索文字列
     * @param string $categoryId カテゴリID
     * @return array 分類一覧（select2用id/textペア）
     */
    public function find2ListByCategory($search, $categoryId = null)
    {
        $query = $this->modelTable->find('valid');

        if (!is_null($search) && $search !== '' && $search != '*') {
            $query->andWhere(function($exp) use ($search) {
                return $exp->or_([
                    'kname like ' => '%' . $search . '%',
                    'name like ' => '%' . $search . '%',
                ]);
            });
        }

        if (!is_null($categoryId) && $categoryId !== '') {
            $query->andWhere([
                'category_id' => $categoryId
            ]);
        }
        $list = $query->all();

        $classifications = [];
        foreach($list as $item) {
            array_push($classifications, [
                'id'   => $item['id'],
                'text' => $item['kname']
            ]);
        }

        return $classifications;
    }

    /**
     * 指定された分類IDとカテゴリIDのデータが存在するかどうかを検証する
     *  
     * - - -
     * @param string $classificationId 分類ID
     * @param string $categoryId カテゴリID
     * @return boolean true:正常/false:異常（指定された分類とカテゴリに関連性がない）
     */
    public function validateClassificationAndCategory($classificationId, $categoryId)
    {
        $count = $this->modelTable->find('valid')
            ->where([
                'id' => $classificationId,
                'category_id' => $categoryId
            ])
            ->count();

        return ($count == 0) ? false : true;
    }

}
