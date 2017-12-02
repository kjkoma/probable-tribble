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
 * Organizations API Controller
 *
 */
class ApiOrganizationsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadModelComponent('ModelOrganizations');
    }

    /**
     * 指定された資産管理組織IDの資産管理組織情報を取得する
     *
     */
    public function organization()
    {
        $data = $this->validateParameter('organization_id', ['post']);
        if (!$data) return;

        // 資産管理組織を取得
        $organization = $this->ModelOrganizations->get($data['organization_id']);
        if (!$organization) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_ORGANIZATION', $data, 404);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['organization' => $organization]);
    }

    /**
     * 送信された資産管理組織データを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('organization', ['post']);
        if (!$data) return;

        $user_id   = $this->AppUser->user()['id'];
        $organization  = $data['organization'];
        $organization['domain_id'] = $this->AppUser->current();

        try {
            // 資産管理組織を保存
            $newOrganization = $this->ModelOrganizations->add($organization, $user_id);
            $this->AppError->result($newOrganization);

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
        $this->setResponse(true, 'your request is success', ['organization' => $newOrganization['data']]);
    }

    /**
     * 送信された資産管理組織データを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('organization', ['post']);
        if (!$data) return;

        $user_id   = $this->AppUser->user()['id'];
        $organization  = $data['organization'];

        try {
            // 資産管理組織を保存
            $updateOrganization = $this->ModelOrganizations->save($organization, $user_id);
            $this->AppError->result($updateOrganization);

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
        $this->setResponse(true, 'your request is success', ['organization' => $updateOrganization['data']]);
    }

    /**
     * 指定された資産管理組織IDの資産管理組織データを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        $organization_id = $data['id'];

        // トランザクション開始
        $this->ModelOrganizations->begin();

        try {
            // 資産管理組織を削除(TableのDependencyを利用して依存データを削除)
            $deleteOrganization = ($this->AppError->has()) ? null : $this->ModelOrganizations->delete($organization_id);
            $this->AppError->result($deleteOrganization);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelOrganizations->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelOrganizations->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelOrganizations->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['organization' => $deleteOrganization['data']]);
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
        $validate = $this->ModelOrganizations->validateKname($data['kname'], $data['id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }
}
