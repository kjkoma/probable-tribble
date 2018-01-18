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
 * Susers API Controller
 *
 */
class ApiSusersController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('SysModelSusers');
        $this->_loadComponent('SysModelSuserDomains');
    }

    /**
     * 指定されたユーザーIDのユーザー情報を取得する
     *
     */
    public function suser()
    {
        $data = $this->validateParameter('suser_id', ['post']);
        if (!$data) return;

        // ユーザーを取得
        $suser = $this->SysModelSusers->get($data['suser_id']);
        if (!$suser) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_SUSER', $data, 404);
            return;
        }

        // ユーザーのドメインを取得
        $suserDomains = $this->SysModelSuserDomains->findBySuserId($data['suser_id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['suser' => $suser, 'suserDomains' => $suserDomains]);
    }

    /**
     * 送信されたユーザーデータを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('suser', ['post']);
        if (!$data) return;

        $suser     = $data['suser'];
        $domains   = array_key_exists('suser_domains', $data) ? $data['suser_domains'] : null;

        // トランザクション開始
        $this->SysModelSusers->begin();

        try {
            // ユーザーを保存
            $newSuser = $this->SysModelSusers->add($suser);
            $this->AppError->result($newSuser);

            // ユーザードメインを保存
            $newDomains = ($this->AppError->has()) ? null : $this->SysModelSuserDomains->addBySuserId($newSuser['data']['id'], $domains);
            $this->AppError->result($newDomains);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->SysModelSusers->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->SysModelSusers->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->SysModelSusers->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['suser' => $newSuser['data']]);
    }

    /**
     * 送信されたユーザーデータを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('suser', ['post']);
        if (!$data) return;

        $suser     = $data['suser'];
        $domains   = array_key_exists('suser_domains', $data) ? $data['suser_domains'] : null;

        // トランザクション開始
        $this->SysModelSusers->begin();

        try {
            // ユーザーを保存
            $updateSuser = $this->SysModelSusers->save($suser);
            $this->AppError->result($updateSuser);

            if (!$this->AppError->has() && $domains) {
                // ユーザードメインを削除
                $deleteDomains = $this->SysModelSuserDomains->deleteBySuserId($suser['id']);
                $this->AppError->result($deleteDomains);

                // ユーザードメインを保存
                $updateDomains = ($this->AppError->has()) ? null : $this->SysModelSuserDomains->addBySuserId($suser['id'], $domains);
                $this->AppError->result($updateDomains);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->SysModelSusers->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->SysModelSusers->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->SysModelSusers->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['suser' => $updateSuser['data']]);
    }

    /**
     * 指定されたユーザーIDのユーザーデータを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        $suser_id = $data['id'];

        // トランザクション開始
        $this->SysModelSusers->begin();

        try {
            // ユーザーを削除(TableのDependencyを利用して依存データを削除)
            $deleteSuser = ($this->AppError->has()) ? null : $this->SysModelSusers->delete($suser_id);
            $this->AppError->result($deleteSuser);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->SysModelSusers->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->SysModelSusers->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->SysModelSusers->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['suser' => $deleteSuser['data']]);
    }

    /**
     * ユーザー一覧を検索する（select2選択用）
     *
     */
    public function findList()
    {
        $data = $this->request->getData();
        if (!$data || !array_key_exists('term', $data) || !$this->request->is('post')) {
            $this->setResponse(true, 'your request is succeed but no parameter found', ['susers' => []]);
            return;
        }

        // ユーザーを検索する
        $susers = $this->SysModelSusers->find2List($data['term']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['susers' => $susers]);
    }

    /**
     * 指定された識別子（email）がユニークかどうかを取得する
     *
     */
    public function validateEmail()
    {
        $data = $this->validateParameter('email', ['post']);
        if (!$data) return;

        // Emailのユニーク性をチェック
        $validate = $this->SysModelSusers->validateEmail($data['email'], $data['id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
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
        $validate = $this->SysModelSusers->validateKname($data['kname'], $data['id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }
}
