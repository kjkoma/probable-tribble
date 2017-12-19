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

use Cake\Core\Configure;

/**
 * 分類階層（ClassTree）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelClassTreeComponent extends AppModelComponent
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'ClassTree';
        parent::initialize($config);
    }

    /**
     * ルート直下の分類を取得する
     *  
     * - - -
     * @param integer $categoryId カテゴリーID
     * @return array ルート分類（名称付）配列
     */
    public function root($categoryId)
    {
        $list = $this->modelTable->find('sorted')
            ->where([$this->modelTable->getAlias() . '.category_id' => $categoryId])
            ->contain(['Ancestor', 'Descendant'])
            ->order(['Ancestor.kname' => 'ASC'])
            ->all();

        return $this->makeTreeArray($list);
    }

    /**
     * 指定分類直下の分類を取得する
     *  
     * - - -
     * @param integer $classificationId 分類ID
     * @return array 配下の分類（名称付）配列
     */
    public function tree($classificationId)
    {
        $list = $this->modelTable->find('sorted')
            ->where([
                $this->modelTable->getAlias() . '.ancestor'      => $classificationId,
                $this->modelTable->getAlias() . '.descendant <>' => $classificationId,
                $this->modelTable->getAlias() . '.neighbor'      => Configure::read('WNote.DB.Neighbor.true')
            ])
            ->contain(['Descendant'])
            ->order(['Descendant.kname' => 'ASC'])
            ->all();

        return $this->makeTreeArray($list);
    }

    /**
     * 指定された分類の先祖を取得する
     *  
     * - - -
     * @param integer $classificationId 分類
     * @param boolean $withMyself true:自分自身を含む|false:自分自身を含まない
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 自身を含む先祖の分類階層一覧
     */
    public function ancestor($classificationId, $withMyself = false, $toArray = false) {
        $query = $this->modelTable->find('sorted')
            ->where(['descendant' => $classificationId]);

        if (!$withMyself) {
            $query->where(['ancestor <>' => $classificationId]);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 指定された分類の子孫を取得する
     *  
     * - - -
     * @param integer $classificationId 分類
     * @param boolean $withMyself true:自分自身を含む|false:自分自身を含まない
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 自身を含む先祖の分類階層一覧
     */
    public function descendant($classificationId, $withMyself = false, $toArray = false) {
        $query = $this->modelTable->find('sorted')
            ->where(['ancestor' => $classificationId]);

        if (!$withMyself) {
            $query->where(['descendant <>' => $classificationId]);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 自分自身データを取得する
     *  
     * - - -
     * @param integer $classificationId 自身の分類ID
     * @return \App\Model\Entity\ClassTree 分類階層情報
     */
    public function myself($classificationId)
    {
        return $this->modelTable->find()
            ->where([
                'ancestor'   => $classificationId,
                'descendant' => $classificationId
            ])
            ->first();
    }

    /**
     * 親データを取得する
     *  
     * - - -
     * @param integer $classificationId 子の分類ID
     * @return \App\Model\Entity\ClassTree 分類階層情報
     */
    public function myparent($classificationId)
    {
        return $this->modelTable->find()
            ->where([
                'descendant' => $classificationId,
                'neighbor'   => Configure::read('WNote.DB.Neighbor.true')
            ])
            ->first();
    }

    /**
     * 指定された分類のカテゴリデータを取得する
     *  
     * - - -
     * @param integer $classificationId 分類ID
     * @return \App\Model\Entity\ClassTree 分類階層情報
     */
    public function category($classificationId)
    {
        $ancestor = [];
        $list = $this->ancestor($classificationId, true);
        foreach($list as $item) {
            array_push($ancestor, $item['ancestor']);
        }

        return $this->modelTable->find()
            ->where([
                'ancestor IN ' => $ancestor,
                'ancestor = descendant',
                'category_id IS NOT' => null
            ])
            ->first();
    }

    /**
     * 子データを取得する
     *  
     * - - -
     * @param integer $classificationId 親の分類ID
     * @return array 分類階層情報
     */
    public function children($classificationId)
    {
        return $this->modelTable->find()
            ->where([
                'ancestor' => $classificationId,
                'neighbor' => Configure::read('WNote.DB.Neighbor.true')
            ])
            ->all();
    }

    /**
     * 関係データを取得する
     *  
     * - - -
     * @param integer $ancestor   先祖
     * @param integer $descendant 子孫
     * @return \App\Model\Entity\ClassTree 分類階層情報
     */
    public function relationship($ancestor, $descendant)
    {
        return $this->modelTable->find()
            ->where([
                'ancestor'   => $ancestor,
                'descendant' => $descendant
            ])
            ->first();
    }

    /**
     * 分類階層データを登録する
     *  
     * - - -
     * @param array $tree 分類階層データ
     * @return array 保存結果（result:true/false, errors:エラー内容, data: 保存データ）
     */
    public function addTree($tree) {
        $parentId = $tree['parent_id'];
        $data = [
            'domain_id'  => $this->current(),
            'descendant' => $tree['id']
        ];

        if (!empty($parentId)) {
            // 最上位以外への登録の場合、先祖データに自身を追加する
            $ancestors = $this->ancestor($parentId, true);
            foreach($ancestors as $ancestor) {
                $data['ancestor'] = $ancestor['ancestor'];
                $data['neighbor'] = ($ancestor['ancestor'] == $parentId) ? Configure::read('WNote.DB.Neighbor.true') : Configure::read('WNote.DB.Neighbor.false');
                $result = $this->add($data);
                if (!$result['result']) {
                    return $result;
                }
            }
        } else {
            // 最上位の場合はカテゴリー情報を付加する
            $data['category_id'] = $tree['category_id'];
        }

        // 自身を追加
        $data['ancestor'] = $tree['id'];
        $data['neighbor'] = Configure::read('WNote.DB.Neighbor.false');
        return $this->add($data);
    }

    /**
     * 分類階層データを編集する
     *  
     * - - -
     * @param array $tree 分類階層データ
     * @return array 保存結果（result:true/false, errors:エラー内容, data: 保存データ）
     */
    public function editTree($tree) {
        $classificationId = $tree['id'];
        $myself           = $this->myself($classificationId);

        // 変更前のカテゴリーIDを削除
        $myself['category_id'] = null;
        $result = $this->save($myself->toArray());
        if (!$result['result']) {
            return $result;
        }

        // 変更前の先祖削除
        $result = $this->deleteAncestor($classificationId);
        if (!$result['result']) {
            return $result;
        }

        // 変更後のカテゴリーIDを設定
        if (empty($tree['parent_id'])) {
            // 変更先の親がルートの場合
            $myself->set('category_id', $tree['category_id']);
            $result = $this->save($myself->toArray());
            if (!$result['result']) {
                return $result;
            }
        }

        // 変更後の先祖を登録
        if (!empty($tree['parent_id'])) {
            $result = $this->addAncestor($classificationId, $tree['parent_id']);
        }

        return $result;
    }

    /**
     * 分類階層データを削除する
     *  
     * - - -
     * @param integer $classificationId 削除する分類のID
     * @return array 保存結果（result:true/false, errors:エラー内容, data: 保存データ）
     */
    public function deleteTree($classificationId) {
        // 自分自身の削除
        $result = $this->deleteAll(['ancestor' => $classificationId]);
        if (!$result['result']) {
            return $result;
        }

        return $this->deleteAll(['descendant' => $classificationId]);
    }

    /**
     * 指定された分類IDに対して指定した親と先祖を登録する
     *  
     * - - -
     * @param integer $classificationId 分類ID
     * @param integer $parentId 親ID
     * @return array 保存結果（result:true/false, errors:エラー内容, data: 保存データ）
     */
    public function addAncestor($classificationId, $parentId) {
        $result = ['result' => true];
        $descendant = $this->descendant($classificationId);
        $ancestors  = $this->ancestor($parentId, true);

        $tree = [];
        $tree['domain_id']  = $this->current();

        foreach($ancestors as $ancestor) {
            $tree['ancestor'] = $ancestor['ancestor'];
            $tree['neighbor'] = Configure::read('WNote.DB.Neighbor.false');

            // 子孫の先祖データを登録
            foreach($descendant as $child) {
                $tree['descendant'] = $child['descendant'];
                $result = $this->add($tree);
                if (!$result['result']) {
                    return $result;
                }
            }

            // 先祖データを登録
            $tree['descendant'] = $classificationId;
            $tree['neighbor']   = ($ancestor['ancestor'] == $parentId) ? Configure::read('WNote.DB.Neighbor.true') : $tree['neighbor'];
            $result = $this->add($tree);
            if (!$result['result']) {
                return $result;
            }
        }

        return $result;
    }

    /**
     * 指定された分類IDの先祖を削除する
     *  
     * - - -
     * @param integer $classificationId  分類ID
     * @return array 保存結果（result:true/false, errors:エラー内容, data: 保存データ）
     */
    public function deleteAncestor($classificationId) {
        $result = ['result' => true];
        $descendant = $this->descendant($classificationId);
        $ancestors  = $this->ancestor($classificationId);

        foreach($ancestors as $ancestor) {
            // 子孫の先祖データを削除
            foreach($descendant as $child) {
                $result = $this->deleteAll(['ancestor' => $ancestor['ancestor'], 'descendant' => $child['descendant']]);
                if (!$result['result']) {
                    return $result;
                }
            }

            // 先祖データを削除
            $result = $this->deleteAll(['ancestor' => $ancestor['ancestor'], 'descendant' => $classificationId]);
            if (!$result['result']) {
                return $result;
            }
        }

        return $result;
    }

    /**
     * 階層の変更有無をチェックする
     *  
     * - - -
     * @param array $tree 分類階層データ
     * @return boolean true:変更あり|false:変更なし
     */
    public function isEdit($tree) {
        $result = false;
        $myself = $this->myself($tree['id']);

        // カテゴリの変更
        if (!$result && !empty($myself['category_id']) && !empty($tree['category_id'])) {
            if ($myself['category_id'] != $tree['category_id']) {
                $result = true;
            }
        }

        // 親分類の変更
        $myparent = (!$result) ? $this->myparent($tree['id']) : [];
        if (!$result && count($myparent) == 0 && !empty($tree['parent_id'])) {
            $result = true;
        }
        if (!$result && count($myparent) > 0 && empty($tree['parent_id'])) {
            $result = true;
        }
        if (!$result && count($myparent) > 0 && $myparent['ancestor'] != $tree['parent_id']) {
            $result = true;
        }

        return $result;
    }

    /**
     * ビューのツリーノード表示形式の配列を作成する
     *  
     * - - -
     * @param array $list 分類一覧のResultSetオブジェクト
     * @return array ツリーノード表示形式の配列
     */
    public function makeTreeArray($list) {
        $result = [];
        foreach($list as $item) {
            array_push($result, [
                'id'              => $item['id'],
                'ancestor'        => $item['ancestor'],
                'descendant'      => $item['descendant'],
                'ancestor_name'   => $item['classification']['kname'],
                'descendant_name' => $item['classification']['kname']
            ]);
        }

        return $result;
    }

}
