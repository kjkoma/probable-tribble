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
 * 資産管理組織階層（OrganizationTree）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelOrganizationTreeComponent extends AppModelComponent
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
        $config['modelName'] = 'OrganizationTree';
        parent::initialize($config);
    }

    /**
     * ルート直下の組織を取得する
     *  
     * - - -
     * @param integer $customerId 資産管理会社ID
     * @return array ルート組織（名称付）配列
     */
    public function root($customerId)
    {
        $list = $this->modelTable->find('sorted')
            ->where([$this->modelTable->getAlias() . '.customer_id' => $customerId])
            ->contain(['Ancestor', 'Descendant'])
            ->order(['Ancestor.kname' => 'ASC'])
            ->all();

        $result = [];
        foreach($list as $item) {
            array_push($result, [
                'id'              => $item['id'],
                'ancestor'        => $item['ancestor'],
                'descendant'      => $item['descendant'],
                'ancestor_name'   => $item['organization']['kname'],
                'descendant_name' => $item['organization']['kname']
            ]);
        }

        return $result;
    }

    /**
     * 指定組織直下の組織を取得する
     *  
     * - - -
     * @param integer $organizationId 資産管理組織ID
     * @return array 配下の組織（名称付）配列
     */
    public function tree($organizationId)
    {
        $list = $this->modelTable->find('sorted')
            ->where([
                $this->modelTable->getAlias() . '.ancestor'      => $organizationId,
                $this->modelTable->getAlias() . '.descendant <>' => $organizationId,
                $this->modelTable->getAlias() . '.neighbor'      => Configure::read('WNote.DB.Neighbor.true')
            ])
            ->contain(['Descendant'])
            ->order(['Descendant.kname' => 'ASC'])
            ->all();

        $result = [];
        foreach($list as $item) {
            array_push($result, [
                'id'              => $item['id'],
                'ancestor'        => $item['ancestor'],
                'descendant'      => $item['descendant'],
                'ancestor_name'   => $item['organization']['kname'],
                'descendant_name' => $item['organization']['kname']
            ]);
        }

        return $result;
    }

    /**
     * 指定された組織の先祖を取得する
     *  
     * - - -
     * @param integer $organizationId 組織
     * @param boolean $withMyself true:自分自身を含む|false:自分自身を含まない
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 自身を含む先祖の組織階層一覧
     */
    public function ancestor($organizationId, $withMyself = false, $toArray = false) {
        $query = $this->modelTable->find('sorted')
            ->where(['descendant' => $organizationId]);

        if (!$withMyself) {
            $query->where(['ancestor <>' => $organizationId]);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 指定された組織の子孫を取得する
     *  
     * - - -
     * @param integer $organizationId 組織
     * @param boolean $withMyself true:自分自身を含む|false:自分自身を含まない
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 自身を含む先祖の組織階層一覧
     */
    public function descendant($organizationId, $withMyself = false, $toArray = false) {
        $query = $this->modelTable->find('sorted')
            ->where(['ancestor' => $organizationId]);

        if (!$withMyself) {
            $query->where(['descendant <>' => $organizationId]);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 自分自身データを取得する
     *  
     * - - -
     * @param integer $organizationId 自身の組織ID
     * @return \App\Model\Entity\OrganizationTree 組織階層情報
     */
    public function myself($organizationId)
    {
        return $this->modelTable->find()
            ->where([
                'ancestor'   => $organizationId,
                'descendant' => $organizationId
            ])
            ->first();
    }

    /**
     * 親データを取得する
     *  
     * - - -
     * @param integer $organizationId 子の組織ID
     * @return \App\Model\Entity\OrganizationTree 組織階層情報
     */
    public function myparent($organizationId)
    {
        return $this->modelTable->find()
            ->where([
                'descendant' => $organizationId,
                'neighbor'   => Configure::read('WNote.DB.Neighbor.true')
            ])
            ->first();
    }

    /**
     * 子データを取得する
     *  
     * - - -
     * @param integer $organizationId 親の組織ID
     * @return array 組織階層情報
     */
    public function children($organizationId)
    {
        return $this->modelTable->find()
            ->where([
                'ancestor' => $organizationId,
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
     * @return \App\Model\Entity\OrganizationTree 組織階層情報
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
     * 組織階層データを登録する
     *  
     * - - -
     * @param array $tree 組織階層データ
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
            // 最上位の場合は資産管理会社情報を付加する
            $data['customer_id'] = $tree['customer_id'];
        }

        // 自身を追加
        $data['ancestor'] = $tree['id'];
        $data['neighbor'] = Configure::read('WNote.DB.Neighbor.false');
        return $this->add($data);
    }

    /**
     * 組織階層データを編集する
     *  
     * - - -
     * @param array $tree 組織階層データ
     * @return array 保存結果（result:true/false, errors:エラー内容, data: 保存データ）
     */
    public function editTree($tree) {
        $organizationId = $tree['id'];
        $myself         = $this->myself($organizationId);

        // 変更前の資産管理会社IDを削除
        $myself->unsetProperty('customer_id');
        $result = $this->save($myself->toArray());
        if (!$result['result']) {
            return $result;
        }

        // 変更前の先祖削除
        $result = $this->deleteAncestor($organizationId);
        if (!$result['result']) {
            return $result;
        }

        // 変更後の資産管理会社IDを設定
        if (empty($tree['parent_id'])) {
            // 変更先の親がルートの場合
            $myself->set('customer_id', $tree['customer_id']);
            $result = $this->save($myself->toArray());
            if (!$result['result']) {
                return $result;
            }
        }

        // 変更後の先祖を登録
        if (!empty($tree['parent_id'])) {
            $result = $this->addAncestor($organizationId, $tree['parent_id']);
        }

        return $result;
    }

    /**
     * 組織階層データを削除する
     *  
     * - - -
     * @param integer $organizationId 削除する組織のID
     * @return array 保存結果（result:true/false, errors:エラー内容, data: 保存データ）
     */
    public function deleteTree($organizationId) {
        $myself   = $this->myself($organizationId);
        $children = $this->children($organizationId);

        // 親子関係の付替
        if (!empty($myself['parent_id'])) {
            // 親がルート以外の場合
            $myparent = $this->myparent($organizationId);
            foreach($children as $child) {
                $relationship = $this->relationship($myparent['ancestor'], $child['descendant']);
                $relationship['neighbor'] = Configure::read('WNote.DB.Neighbor.true');
                $result = $this->save($relationship->toArray());
                if (!$result['result']) {
                    return $result;
                }
            }
        } else {
            // 親がルートの場合
            foreach($children as $child) {
                $child['customer_id'] = $myself['customer_id'];
                $result = $this->save($child);
                if (!$result['result']) {
                    return $result;
                }
            }
        }

        // 自分自身の削除
        $result = $this->deleteAll(['ancestor' => $organizationId]);
        if (!$result['result']) {
            return $result;
        }

        return $this->deleteAll(['descendant' => $organizationId]);
    }

    /**
     * 指定された組織IDに対して指定した親と先祖を登録する
     *  
     * - - -
     * @param integer $organizationId 組織ID
     * @param integer $parentId 親ID
     * @return array 保存結果（result:true/false, errors:エラー内容, data: 保存データ）
     */
    public function addAncestor($organizationId, $parentId) {
        $result = ['result' => true];
        $descendant = $this->descendant($organizationId);
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
            $tree['descendant'] = $organizationId;
            $tree['neighbor']   = ($ancestor['ancestor'] == $parentId) ? Configure::read('WNote.DB.Neighbor.true') : $tree['neighbor'];
            $result = $this->add($tree);
            if (!$result['result']) {
                return $result;
            }
        }

        return $result;
    }

    /**
     * 指定された組織IDの先祖を削除する
     *  
     * - - -
     * @param integer $organizationId  組織ID
     * @return array 保存結果（result:true/false, errors:エラー内容, data: 保存データ）
     */
    public function deleteAncestor($organizationId) {
        $result = ['result' => true];
        $descendant = $this->descendant($organizationId);
        $ancestors  = $this->ancestor($organizationId);

        foreach($ancestors as $ancestor) {
            // 子孫の先祖データを削除
            foreach($descendant as $child) {
                $result = $this->deleteAll(['ancestor' => $ancestor['ancestor'], 'descendant' => $child['id']]);
                if (!$result['result']) {
                    return $result;
                }
            }

            // 先祖データを削除
            $result = $this->deleteAll(['ancestor' => $ancestor['ancestor'], 'descendant' => $organizationId]);
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
     * @param array $tree 組織階層データ
     * @return boolean true:変更あり|false:変更なし
     */
    public function isEdit($tree) {
        $result = false;
        $myparent = $this->myparent($tree['id']);

        // 親組織の変更
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

}
