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
namespace App\Controller\Api\Master\System;

use \Exception;
use App\Controller\Api\ApiController;

/**
 * Domains API Controller
 *
 */
class ApiDomainsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('SysModelDomains');
        $this->_loadComponent('ModelDomainApps');
    }

    /**
     * 指定されたドメインIDのドメイン情報を取得する
     *
     */
    public function domain()
    {
        $data = $this->validateParameter('domain_id', ['post']);
        if (!$data) return;

        // ドメインを取得
        $domain = $this->SysModelDomains->get($data['domain_id']);
        if (!$domain) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_DOMAIN', $data, 404);
            return;
        }

        // ドメインアプリケーションを取得
        $domainApps = $this->ModelDomainApps->findByDomainId($data['domain_id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['domain' => $domain, 'domainApps' => $domainApps]);
    }

    /**
     * 送信されたドメインデータを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('domain', ['post']);
        if (!$data) return;

        $domain    = $data['domain'];
        $apps      = array_key_exists('domain_apps', $domain) ? $domain['domain_apps'] : null;

        // トランザクション開始
        $this->SysModelDomains->begin();

        try {
            // ドメインを保存
            $newDomain = $this->SysModelDomains->add($domain);
            $this->AppError->result($newDomain);

            // ドメインアプリケーションを保存
            $newApps = ($this->AppError->has()) ? null : $this->ModelDomainApps->addByDomainId($newDomain['data']['id'], $apps);
            $this->AppError->result($newApps);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->SysModelDomains->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->SysModelDomains->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->SysModelDomains->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['domain' => $newDomain['data']]);
    }

    /**
     * 送信されたドメインデータを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('domain', ['post']);
        if (!$data) return;

        $domain    = $data['domain'];
        $apps      = array_key_exists('domain_apps', $domain) ? $domain['domain_apps'] : null;

        // トランザクション開始
        $this->SysModelDomains->begin();

        try {
            // ドメインを保存
            $updateDomain = $this->SysModelDomains->save($domain);
            $this->AppError->result($updateDomain);

            if (!$this->AppError->has() && $apps) {
                // ドメインアプリケーションを削除
                $deleteApps = $this->ModelDomainApps->deleteByDomainId($domain['id']);
                $this->AppError->result($deleteApps);

                // ドメインアプリケーションを保存
                $updateApps = ($this->AppError->has()) ? null : $this->ModelDomainApps->addByDomainId($domain['id'], $apps);
                $this->AppError->result($updateApps);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->SysModelDomains->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->SysModelDomains->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->SysModelDomains->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['domain' => $updateDomain['data']]);
    }

    /**
     * 指定されたドメインIDのドメインデータを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        $domain_id = $data['id'];

        // トランザクション開始
        $this->SysModelDomains->begin();

        try {
            // ドメインを削除(TableのDependencyを利用して依存データを削除)
            $deleteDomain = ($this->AppError->has()) ? null : $this->SysModelDomains->delete($domain_id);
            $this->AppError->result($deleteDomain);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->SysModelDomains->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->SysModelDomains->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->SysModelDomains->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['domain' => $deleteDomain['data']]);
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
        $validate = $this->SysModelDomains->validateKname($data['kname'], $data['id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }
}
