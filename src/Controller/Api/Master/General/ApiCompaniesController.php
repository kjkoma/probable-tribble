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
namespace App\Controller\Api\Master\General;

use \Exception;
use App\Controller\Api\ApiController;

/**
 * Companies API Controller
 *
 */
class ApiCompaniesController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelCompanies');
    }

    /**
     * 指定された企業IDの企業情報を取得する
     *
     */
    public function company()
    {
        $data = $this->validateParameter('company_id', ['post']);
        if (!$data) return;

        // 企業を取得
        $company = $this->ModelCompanies->get($data['company_id']);
        if (!$company) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_COMPANY', $data, 404);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['company' => $company]);
    }

    /**
     * 送信された企業データを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('company', ['post']);
        if (!$data) return;

        $company = $data['company'];
        $company['domain_id'] = $this->AppUser->current();

        try {
            // 企業を保存
            $newCompany = $this->ModelCompanies->add($company);
            $this->AppError->result($newCompany);

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
        $this->setResponse(true, 'your request is success', ['company' => $newCompany['data']]);
    }

    /**
     * 送信された企業データを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('company', ['post']);
        if (!$data) return;

        $company = $data['company'];

        try {
            // 企業を保存
            $updateCompany = $this->ModelCompanies->save($company);
            $this->AppError->result($updateCompany);

            // エラー判定
            if ($this->AppError->has()) {
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

        } catch(Exception $e) {
            // ロールバック
            $this->ModelCompanies->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['company' => $updateCompany['data']]);
    }

    /**
     * 指定された企業IDの企業データを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        $company_id = $data['id'];

        // トランザクション開始
        $this->ModelCompanies->begin();

        try {
            // 企業を削除(TableのDependencyを利用して依存データを削除)
            $deleteCompany = ($this->AppError->has()) ? null : $this->ModelCompanies->delete($company_id);
            $this->AppError->result($deleteCompany);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelCompanies->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelCompanies->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelCompanies->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['company' => $deleteCompany['data']]);
    }

}
