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
namespace App\Controller\Api\Instock;

use \Exception;
use App\Controller\Api\ApiController;

/**
 * Instock Plans API Controller
 *
 */
class ApiInstockPlansController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelInstockPlans');
    }

    /**
     * 指定された入庫予定IDの入庫予定情報を取得する
     *
     */
    public function plan()
    {
        $data = $this->validateParameter('plan_id', ['post']);
        if (!$data) return;

        // 入庫予定を取得
        $plan = $this->ModelInstockPlans->get($data['plan_id']);
        if (!$plan) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_INSTOCK_PLAN', $data, 404);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['plan' => $plan]);
    }

    /**
     * 入庫予定一覧を取得する
     *
     */
    public function plans()
    {
        if (!$this->request->is('post')) {
            $this->setError('指定されたHTTPメソッドには対応していません。', 'UNSUPPORTED_HTTP_METHOD');
            return;
        }

        // 入庫予定を取得
        $plans = $this->ModelInstockPlans->list();

        // 一覧表示用に編集する
        foreach($plans as $plan) {
            // 注) cakephpの仕様によりstsの最後のsが削除されてしまうのでinstock_plans_stで取得する
            $plan['instock_kbn_name'] = $plan['instock_plans_kbn']['name'];
            $plan['plan_sts_name']    = $plan['instock_plans_st']['name'];
            $plan['asset_no']         = ($plan['instock_plan_details']) ? $plan['instock_plan_details'][0]['asset']['asset_no']  : '';
            $plan['serial_no']        = ($plan['instock_plan_details']) ? $plan['instock_plan_details'][0]['asset']['serial_no'] : '';
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['plans' => $plans]);
    }

    /**
     * 入庫予定一覧(新規・返却のみ)を取得する
     *
     */
    public function plansNew()
    {
        if (!$this->request->is('post')) {
            $this->setError('指定されたHTTPメソッドには対応していません。', 'UNSUPPORTED_HTTP_METHOD');
            return;
        }

        // 入庫予定を取得
        $plans = $this->ModelInstockPlans->listNew();

        // 一覧表示用に編集する
        $list = [];
        foreach($plans as $plan) {
            // 注) cakephpの仕様によりstsの最後のsが削除されてしまうのでinstock_plans_stで取得する
            $plan['instock_kbn_name'] = $plan['instock_plans_kbn']['name'];
            $plan['plan_sts_name']    = $plan['instock_plans_st']['name'];
            $plan['plan_count']       = ($plan['instock_plan_details']) ? $plan['instock_plan_details'][0]['sum_plan_count'] : '0';
            $plan['instock_count']    = ($plan['instocks']) ? $plan['instocks'][0]['sum_instock_count'] : '0';
            $list[] = $plan;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['plans' => $list]);
    }

    /**
     * 入庫可能な入庫予定一覧を取得する
     *
     */
    public function plansInstock()
    {
        if (!$this->request->is('post')) {
            $this->setError('指定されたHTTPメソッドには対応していません。', 'UNSUPPORTED_HTTP_METHOD');
            return;
        }

        // 入庫予定を取得
        $plans = $this->ModelInstockPlans->listInstock();

        // 一覧表示用に編集する
        $list = [];
        foreach($plans as $plan) {
            // 注) cakephpの仕様によりstsの最後のsが削除されてしまうのでinstock_plans_stで取得する
            $plan['instock_kbn_name'] = $plan['instock_plans_kbn']['name'];
            $plan['plan_sts_name']    = $plan['instock_plans_st']['name'];
            $plan['plan_count']       = ($plan['instock_plan_details']) ? $plan['instock_plan_details'][0]['sum_plan_count'] : '0';
            $plan['instock_count']    = ($plan['instocks']) ? $plan['instocks'][0]['sum_instock_count'] : '0';
            $list[] = $plan;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['plans' => $list]);
    }

    /**
     * 送信された入庫予定データを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('plan', ['post']);
        if (!$data) return;

        $plan = $data['plan'];
        $plan['domain_id'] = $this->AppUser->current();

        try {
            // 入庫予定を保存
            $newPlan = $this->ModelInstockPlans->addNew($plan);
            $this->AppError->result($newPlan);

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
        $this->setResponse(true, 'your request is success', ['plan' => $newPlan['data']]);
    }

    /**
     * 送信された入庫予定データを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('plan', ['post']);
        if (!$data) return;

        $plan = $data['plan'];

        try {
            // 入庫予定を保存
            $updatePlan = $this->ModelInstockPlans->save($plan);
            $this->AppError->result($updatePlan);

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
        $this->setResponse(true, 'your request is success', ['plan' => $updatePlan['data']]);
    }

    /**
     * 指定された入庫予定IDの入庫予定データを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        $plan_id = $data['id'];

        // トランザクション開始
        $this->ModelInstockPlans->begin();

        try {
            // 入庫予定を削除(TableのDependencyを利用して依存データを削除)
            $deletePlan = ($this->AppError->has()) ? null : $this->ModelInstockPlans->delete($plan_id);
            $this->AppError->result($deletePlan);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelInstockPlans->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelInstockPlans->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelInstockPlans->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['plan' => $deletePlan['data']]);
    }

    /**
     * 指定された入庫予定が取消可能かどうかを取得する
     *
     */
    public function validateInstockCancel()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        // 取消可否を取得
        $validate = $this->ModelInstockPlans->validateCancel($data['id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }
}
