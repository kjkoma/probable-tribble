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
     * 分類を検索する（kname or name）
     *  
     * - - -
     * @param string $search 検索文字列
     * @param string $classificationId 分類ID（指定分類配下を取得しない場合に指定）
     * @return array 分類一覧（select2用id/textペア）
     */
    public function find2List($search, $classificationId = null)
    {
        $descendant = [];
        if (!is_null($classificationId)) {
            $list = $this->ModelClassTree->descendant($classificationId, true);
            foreach($list as $item) {
                array_push($descendant, $item['descendant']);
            }
        }

        $query = $this->modelTable->find('valid')
            ->andWhere(function($exp) use ($search) {
                return $exp->or_([
                    'kname like ' => '%' . $search . '%',
                    'name like ' => '%' . $search . '%',
                ]);
            });
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


}
