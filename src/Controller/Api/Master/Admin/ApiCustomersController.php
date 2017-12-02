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
namespace App\Controller\Api\Master\Admin;

use \Exception;
use App\Controller\Api\ApiController;

/**
 * Customers API Controller
 *
 */
class ApiCustomersController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadModelComponent('ModelCustomers');
    }

    /**
     * 指定された資産管理会社IDの資産管理会社情報を取得する
     *
     */
    public function customer()
    {
        $data = $this->validateParameter('customer_id', ['post']);
        if (!$data) return;

        // 資産管理会社を取得
        $customer = $this->ModelCustomers->get($data['customer_id']);
        if (!$customer) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_CUSTOMER', $data, 404);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['customer' => $customer]);
    }

    /**
     * 送信された資産管理会社データを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('customer', ['post']);
        if (!$data) return;

        $user_id   = $this->AppUser->user()['id'];
        $customer  = $data['customer'];
        $customer['domain_id'] = $this->AppUser->current();

        try {
            // 資産管理会社を保存
            $newCustomer = $this->ModelCustomers->add($customer, $user_id);
            $this->AppError->result($newCustomer);

            // エラー判定
            if ($this->AppError->has()) {
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

        } catch(Exception $e) {
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['customer' => $newCustomer['data']]);
    }

    /**
     * 送信された資産管理会社データを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('customer', ['post']);
        if (!$data) return;

        $user_id   = $this->AppUser->user()['id'];
        $customer  = $data['customer'];

        try {
            // 資産管理会社を保存
            $updateCustomer = $this->ModelCustomers->save($customer, $user_id);
            $this->AppError->result($updateCustomer);

            // エラー判定
            if ($this->AppError->has()) {
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

        } catch(Exception $e) {
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['customer' => $updateCustomer['data']]);
    }

    /**
     * 指定された資産管理会社IDの資産管理会社データを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        $customer_id = $data['id'];

        // トランザクション開始
        $this->ModelCustomers->begin();

        try {
            // 資産管理会社を削除(TableのDependencyを利用して依存データを削除)
            $deleteCustomer = ($this->AppError->has()) ? null : $this->ModelCustomers->delete($customer_id);
            $this->AppError->result($deleteCustomer);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelCustomers->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelCustomers->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelCustomers->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['customer' => $deleteCustomer['data']]);
    }

    /**
     * 指定された識別子（kname）がユニークかどうかを取得する
     *
     */
    public function validateKname()
    {
        $data = $this->validateParameter('kname', ['post']);
        if (!$data) return;

        // 識別子のユニーク性をチェック
        $validate = $this->ModelCustomers->validateKname($data['kname'], $data['id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }
}
