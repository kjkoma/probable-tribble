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

use App\Controller\Api\ApiController;
use Exception;

/**
 * Users API Controller
 *
 */
class ApiUsersController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelUsers');
        $this->_loadComponent('ModelOrganizations');
    }

    /**
     * 指定されたユーザーIDのユーザー情報を取得する
     *
     */
    public function user()
    {
        $data = $this->validateParameter('user_id', ['post']);
        if (!$data) return;

        // ユーザーを取得
        $user = $this->ModelUsers->get($data['user_id']);
        if (!$user) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_USER', $data, 404);
            return;
        }

        // 組織名称を取得・付加（ビューのselect2選択BOX用）
        $organization = $this->ModelOrganizations->get($user['organization_id']);
        if (!$organization) {
            $this->setError('指定されたデータに関連する組織情報がありません。', 'NOT_FOUND_ORGANIZATION', $data, 404);
            return;
        }
        $user['organization_text'] = $organization['kname'];

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', [
            'user' => $user
        ]);
    }

    /**
     * 送信されたユーザーデータを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('user', ['post']);
        if (!$data) return;

        $user = $data['user'];
        $user['domain_id'] = $this->AppUser->current();

        // トランザクション開始
        $this->ModelUsers->begin();

        try {
            // 資産管理会社情報を付加
            $organization = $this->ModelOrganizations->get($user['organization_id']);
            if (count($organization) == 0) {
                // ロールバック
                $this->ModelUsers->rollback();
                $this->setResponseError('your organization is not found..', ['organization_id' => $user['organization_id']]);
                return;
            }
            $user['customer_id'] = $organization['customer_id'];

            // ユーザーを保存
            $newUser = $this->ModelUsers->add($user);
            $this->AppError->result($newUser);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelUsers->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelUsers->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelUsers->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['user' => $newUser['data']]);
    }

    /**
     * 送信されたユーザーデータを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('user', ['post']);
        if (!$data) return;

        $user  = $data['user'];

        // トランザクション開始
        $this->ModelUsers->begin();

        try {
            // 資産管理会社情報を付加
            $organization = $this->ModelOrganizations->get($user['organization_id']);
            if (count($organization) == 0) {
                // ロールバック
                $this->ModelUsers->rollback();
                $this->setResponseError('your organization is not found..', ['organization_id' => $user['organization_id']]);
                return;
            }
            $user['customer_id'] = $organization['customer_id'];

            // ユーザーを保存
            $updateUser = $this->ModelUsers->save($user);
            $this->AppError->result($updateUser);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelUsers->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelUsers->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelUsers->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['user' => $updateUser['data']]);
    }

    /**
     * 指定されたユーザーIDのユーザーデータを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelUsers->begin();

        try {
            // ユーザーを削除(TableのDependencyを利用して依存データを削除)
            $deleteUser = $this->ModelUsers->delete($data['id']);
            $this->AppError->result($deleteUser);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelUsers->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelUsers->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelUsers->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['user' => $deleteUser['data']]);
    }

    /**
     * ユーザー一覧を検索する
     *
     */
    public function findList()
    {
        $data = $this->request->getData();
        if (!$data || !array_key_exists('term', $data) || !$this->request->is('post')) {
            $this->setResponse(true, 'your request is succeed but no parameter found', ['users' => []]);
            return;
        }

        // ユーザーを取得する（組織指定がある場合は組織で絞り込む）
        $organizationId = array_key_exists('organization_id', $data) ? $data['organization_id'] : null;
        $users  = $this->ModelUsers->find2List($data['term'], $organizationId);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['users' => $users]);
    }


}
