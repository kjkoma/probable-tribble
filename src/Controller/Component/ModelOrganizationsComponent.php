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
 * 資産管理組織（Organizations）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelOrganizationsComponent extends AppModelComponent
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
        $config['modelName'] = 'Organizations';
        parent::initialize($config);
    }

    /**
     * 資産管理組織情報を取得する
     *  
     * - - -
     * @param integer $organization_id 資産管理組織ID
     * @return \App\Model\Entity\Organization 資産管理組織情報
     */
    public function get($organization_id)
    {
        return $this->modelTable->findById($organization_id)
            ->contain(['Domains', 'Customers'])
            ->first();
    }

}
