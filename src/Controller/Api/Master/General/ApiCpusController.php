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
 * Cpus API Controller
 *
 */
class ApiCpusController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelCpus');
    }

    /**
     * 指定されたCPUIDのCPU情報を取得する
     *
     */
    public function cpu()
    {
        $data = $this->validateParameter('cpu_id', ['post']);
        if (!$data) return;

        // CPUを取得
        $cpu = $this->ModelCpus->get($data['cpu_id']);
        if (!$cpu) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_CPU', $data, 404);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['cpu' => $cpu]);
    }

    /**
     * 送信されたCPUデータを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('cpu', ['post']);
        if (!$data) return;

        $cpu = $data['cpu'];
        $cpu['domain_id'] = $this->AppUser->current();

        try {
            // 企業を保存
            $newCpu = $this->ModelCpus->add($cpu);
            $this->AppError->result($newCpu);

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
        $this->setResponse(true, 'your request is success', ['cpu' => $newCpu['data']]);
    }

    /**
     * 送信されたCPUデータを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('cpu', ['post']);
        if (!$data) return;

        $cpu = $data['cpu'];

        try {
            // CPUを保存
            $updateCpu = $this->ModelCpus->save($cpu);
            $this->AppError->result($updateCpu);

            // エラー判定
            if ($this->AppError->has()) {
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

        } catch(Exception $e) {
            // ロールバック
            $this->ModelCpus->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['cpu' => $updateCpu['data']]);
    }

    /**
     * 指定されたCPUIDのCPUデータを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        $cpu_id = $data['id'];

        // トランザクション開始
        $this->ModelCpus->begin();

        try {
            // ユーザーを削除(TableのDependencyを利用して依存データを削除)
            $deleteCpu = ($this->AppError->has()) ? null : $this->ModelCpus->delete($cpu_id);
            $this->AppError->result($deleteCpu);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelCpus->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelCpus->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelCpus->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['cpu' => $deleteCpu['data']]);
    }

}
