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
 * 資産利用者（Users）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelUsersComponent extends AppModelComponent
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
        $config['modelName'] = 'Users';
        parent::initialize($config);
    }

    /**
     * 指定された資産管理組織に所属するユーザーを取得する
     *  
     * - - -
     * @param integer $organizationId 資産管理組織ID
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array ユーザー一覧
     */
    public function findByOrganizationId($organizationId, $toArray = false)
    {
        $query = $this->modelTable->find('sorted')
            ->where(['organization_id' => $organizationId]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 組織指定でビューのツリーノード表示用のユーザー一覧を取得する
     *  
     * - - -
     * @param integer $organizationId 資産管理組織ID
     * @return array ユーザー一覧
     */
    public function treeNode($organizationId)
    {
        $list = $this->findByOrganizationId($organizationId);

        return $this->makeTreeArray($list);
    }

    /**
     * ビューのツリーノード表示形式の配列を作成する（ユーザーノード用）
     *  
     * - - -
     * @param array $list ユーザー一覧のResultSetオブジェクト
     * @return array ツリーノード表示形式の配列
     */
    public function makeTreeArray($list) {
        $result = [];
        foreach($list as $item) {
            array_push($result, [
                'id'              => $item['id'],
                'sname'           => $item['sname'],
                'fname'           => $item['fname'],
                'organization_id' => $item['organization_id']
            ]);
        }

        return $result;
    }

    /**
     * ユーザーを検索する（kname or name）
     *  
     * - - -
     * @param string $search 検索文字列
     * @param string $organizationId 組織ID
     * @return array 製品一覧（select2用id/textペア）
     */
    public function find2List($search, $organizationId = null)
    {
        $query = $this->modelTable->find('valid');

        if (!is_null($search) && $search !== '') {
            $query->andWhere(function($exp) use ($search) {
                return $exp->or_([
                    'sname like ' => '%' . $search . '%',
                    'fname like ' => '%' . $search . '%',
                ]);
            });
        }

        if (!is_null($organizationId) && $organizationId !== '') {
            $query->andWhere([
                'organization_id' => $organizationId
            ]);
        }
        $list = $query->all();

        $products = [];
        foreach($list as $item) {
            array_push($products, [
                'id'   => $item['id'],
                'text' => $item['sname'] . ' ' . $item['fname']
            ]);
        }

        return $products;
    }
}
