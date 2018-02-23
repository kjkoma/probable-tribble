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
namespace App\Controller\Api\Repair;

use \Exception;
use App\Controller\Api\ApiController;
use Cake\Core\Configure;

/**
 * Repair Histories API Controller
 *
 */
class ApiRepairHistoriesController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelRepairHistories');
        $this->_loadComponent('ModelRepairs');
    }

    /**
     * 修理履歴を取得する
     *
     */
    public function history()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        // 修理履歴一覧を取得
        $history = $this->ModelRepairHistories->get($data['id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['history' => $history]);
    }


    /**
     * 修理IDより修理履歴一覧を取得する
     *
     */
    public function histories()
    {
        $data = $this->validateParameter('repair_id', ['post']);
        if (!$data) return;

        // 修理履歴一覧を取得
        $histories = $this->ModelRepairHistories->histories($data['repair_id']);

        // 一覧用に編集する
        foreach($histories as $history) {
            $history['history_suser_name'] = $history['repair_history_suser']['kname'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['histories' => $histories]);
    }

    /**
     * 送信された修理履歴データを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('history', ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelRepairHistories->begin();

        try {
            // 修理履歴を保存
            $newHistory = $this->ModelRepairHistories->addNew($data['history']);
            $this->AppError->result($newHistory);

            if (!$this->AppError->has()) {
                // 修理情報が入庫済の場合は、修理中に更新
                $newHistory = $this->ModelRepairs->startRepair($newHistory['data']['repair_id']);
                $this->AppError->result($newHistory);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelRepairHistories->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelRepairHistories->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelRepairHistories->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['history' => $newHistory['data']]);
    }

    /**
     * 送信された修理履歴データを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('history', ['post']);
        if (!$data) return;

        try {
            // 修理履歴を保存
            $updateHistory = $this->ModelRepairHistories->edit($data['history']);
            $this->AppError->result($updateHistory);

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
        $this->setResponse(true, 'your request is success', ['history' => $updateHistory['data']]);
    }

    /**
     * 指定された修理履歴IDの修理履歴データを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        try {
            // 修理履歴を削除
            $deleteHistory = $this->ModelRepairHistories->delete($data['id']);
            $this->AppError->result($deleteHistory);

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
        $this->setResponse(true, 'your request is succeed', ['history' => $deleteHistory['data']]);
    }
}
