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
 * 資産管理会社（Customers）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelCustomersComponent extends AppModelComponent
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
        $config['modelName'] = 'Customers';
        parent::initialize($config);
    }

    /**
     * 資産管理会社情報を取得する
     *  
     * - - -
     * @param integer $customer_id 資産管理会社ID
     * @return \App\Model\Entity\Customer 資産管理会社情報
     */
    public function get($customer_id)
    {
        return $this->modelTable->findById($customer_id)
            ->contain(['Domains'])
            ->first();
    }

}
