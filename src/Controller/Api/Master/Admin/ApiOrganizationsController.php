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
        $this->_loadComponent('ModelOrganizations');
        $this->_loadComponent('ModelOrganizationTree');
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

        // 親組織を取得
        $myparent = ['parent_id' => '', 'parent_text' => ''];
        $p = $this->ModelOrganizationTree->myparent($organization['id']);
        if (count($p)) {
            $po = $this->ModelOrganizations->get($p['ancestor']);
            $myparent['parent_id']   = $p['ancestor'];
            $myparent['parent_text'] = $po['kname'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', [
            'organization' => $organization,
            'parent'       => $myparent
        ]);
    }

    /**
     * 送信された資産管理組織データを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('organization', ['post']);
        if (!$data) return;

        $organization = $data['organization'];
        $organization['domain_id'] = $this->AppUser->current();

        // トランザクション開始
        $this->ModelOrganizations->begin();

        try {
            // 資産管理組織を保存
            $newOrganization = $this->ModelOrganizations->add($organization);
            $this->AppError->result($newOrganization);

            // 資産管理組織階層を保存
            $organization['id'] = $newOrganization['data']['id'];
            $newOrganizationTree = ($this->AppError->has()) ? null : $this->ModelOrganizationTree->addTree($organization);
            $this->AppError->result($newOrganizationTree);

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

        $organization  = $data['organization'];

        // トランザクション開始
        $this->ModelOrganizations->begin();

        try {
            // 資産管理組織を保存
            $updateOrganization = $this->ModelOrganizations->save($organization);
            $this->AppError->result($updateOrganization);

            // 資産管理組織階層を保存（変更時のみ）
            if ($this->ModelOrganizationTree->isEdit($organization)) {
                $updateOrganizationTree = ($this->AppError->has()) ? null : $this->ModelOrganizationTree->editTree($organization);
                $this->AppError->result($updateOrganizationTree);
            }

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

        $organizationId = $data['id'];

        // トランザクション開始
        $this->ModelOrganizations->begin();

        try {
            // 資産管理組織を削除(資産管理組織階層以外はTableのDependencyを利用して依存データを削除)
            $deleteOrganization = $this->ModelOrganizations->delete($organizationId);
            $this->AppError->result($deleteOrganization);

            // 資産管理組織階層を削除
            $deleteOrganizationTree = ($this->AppError->has()) ? null : $this->ModelOrganizationTree->deleteTree($organizationId);
            $this->AppError->result($deleteOrganizationTree);

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

    /**
     * 資産管理会社配下のルート組織を取得する
     *
     */
    public function root()
    {
        $data = $this->validateParameter('customer_id', ['post']);
        if (!$data) return;

        // ルート組織を取得する
        $organizations = $this->ModelOrganizationTree->root($data['customer_id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['organizations' => $organizations]);
    }

    /**
     * 指定組織配下の組織を取得する
     *
     */
    public function children()
    {
        $data = $this->validateParameter('organization_id', ['post']);
        if (!$data) return;

        // 配下組織を取得する
        $organizations = $this->ModelOrganizationTree->tree($data['organization_id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['organizations' => $organizations]);
    }

    /**
     * 組織一覧を検索する
     *
     */
    public function findList()
    {
        $data = $this->request->getData();
        if (!$data || !array_key_exists('term', $data) || !$this->request->is('post')) {
            $this->setResponse(true, 'your request is succeed but no parameter found', ['organizations' => []]);
            return;
        }

        // 配下組織を取得する
        $organizations = $this->ModelOrganizations->find2List($data['term'], $data['organization_id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['organizations' => $organizations]);
    }

}
