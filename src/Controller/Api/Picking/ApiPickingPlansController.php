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
namespace App\Controller\Api\Picking;

use \Exception;
use App\Controller\Api\ApiController;
use Cake\Core\Configure;

/**
 * Picking Plans API Controller
 *
 */
class ApiPickingPlansController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelPickingPlans');
        $this->_loadComponent('ModelPickingPlanDetails');
        $this->_loadComponent('ModelAssets');
        $this->_loadComponent('ModelExchanges');
        $this->_loadComponent('ModelRepairs');
    }

    /**
     * 指定された出庫予定IDの出庫予定情報を取得する
     *
     */
    public function plan()
    {
        $data = $this->validateParameter('plan_id', ['post']);
        if (!$data) return;

        // 出庫予定を取得
        $plan = $this->ModelPickingPlans->plan($data['plan_id']);
        if (!$plan) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_PICKING_PLAN', $data, 404);
            return;
        }

        // 各名称を付加（ビューのselect2選択BOX用）
        $plan['apply_no']              = ($plan['picking_plan_details']) ? $plan['picking_plan_details'][0]['apply_no'] : '';
        $plan['req_organization_text'] = $plan['picking_plan_req_organization']['kname'];
        $plan['req_user_text']         = ($plan['picking_plan_req_user']) ? $plan['picking_plan_req_user']['sname'] . ' ' . $plan['picking_plan_req_user']['fname'] : '';
        $plan['use_organization_text'] = $plan['picking_plan_use_organization']['kname'];
        $plan['use_user_text']         = ($plan['picking_plan_use_user']) ? $plan['picking_plan_use_user']['sname'] . ' ' . $plan['picking_plan_use_user']['fname'] : '';
        $plan['dlv_organization_text'] = $plan['picking_plan_dlv_organization']['kname'];
        $plan['dlv_user_text']         = ($plan['picking_plan_dlv_user']) ? $plan['picking_plan_dlv_user']['sname'] . ' ' . $plan['picking_plan_dlv_user']['fname'] : '';
        $plan['rcv_suser_text']        = $plan['picking_plan_rcv_suser']['kname'];
        $plan['category_id']           = ($plan['picking_plan_details']) ? $plan['picking_plan_details'][0]['category_id'] : '';
        $plan['reuse_kbn']             = ($plan['picking_plan_details']) ? $plan['picking_plan_details'][0]['reuse_kbn'] : '';
        $plan['kitting_pattern_id']    = ($plan['picking_plan_details']) ? $plan['picking_plan_details'][0]['kitting_pattern_id'] : '';
        $plan['kitting_pattern_text']  = ($plan['picking_plan_details']) ? $plan['picking_plan_details'][0]['kitting_pattern']['kname'] : '';

        // 故障情報を取得
        $exchange = [];
        if ($plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.exchange')) {
            $exchange = $this->ModelExchanges->byPickingPlanId($plan['id']);
            $exchange['instock_plan_date']  = $exchange['instock_plan']['plan_date'];
            $exchange['asset_no']           = $exchange['instock_plan_detail']['asset']['asset_no'];
            $exchange['serial_no']          = ($exchange['asset_no'] == '') ? $exchange['instock_plan_detail']['asset']['serial_no'] : '';
        }

        // 修理情報を取得
        $repair = [];
        if ($plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.repair')) {
            $repair = $this->ModelRepairs->byPickingPlanId($plan['id']);
            $repair['instock_plan_date']  = $repair['instock_plan']['plan_date'];
            $repair['asset_no']           = $repair['instock_plan_detail']['asset']['asset_no'];
            $repair['serial_no']          = ($repair['asset_no'] == '') ? $repair['instock_plan_detail']['asset']['serial_no'] : '';
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['plan' => $plan, 'exchange' => $exchange, 'repair' => $repair]);
    }

    /**
     * 出庫予定一覧（依頼）を取得する
     *
     */
    public function plansRequest()
    {
        if (!$this->request->is('post')) {
            $this->setError('指定されたHTTPメソッドには対応していません。', 'UNSUPPORTED_HTTP_METHOD');
            return;
        }

        // 出庫予定を取得
        $plans = $this->ModelPickingPlans->requestPlans();

        // 一覧表示用に編集する
        foreach($plans as $plan) {
            $plan['picking_kbn_name']  = $plan['picking_plan_picking_kbn']['name'];
            $plan['plan_sts_name']     = $plan['picking_plan_st']['name'];
            $plan['apply_no']          = $plan['picking_plan_details'][0]['apply_no'];
            $plan['req_user_name']     = $plan['picking_plan_req_user']['sname'] . ' ' . $plan['picking_plan_req_user']['fname'];
            $plan['use_user_name']     = $plan['picking_plan_use_user']['sname'] . ' ' . $plan['picking_plan_use_user']['fname'];
            $plan['dlv_user_name']     = $plan['picking_plan_dlv_user']['sname'] . ' ' . $plan['picking_plan_dlv_user']['fname'];
            $plan['rcv_suser_name']    = $plan['picking_plan_rcv_suser']['kname'];
            $plan['category_name']     = $plan['picking_plan_details'][0]['category']['kname'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['plans' => $plans]);
    }

    /**
     * 送信された出庫予定データを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('plan', ['post']);
        if (!$data) return;

        $plan = $data['plan'];
        $plan['domain_id'] = $this->AppUser->current();

        // 資産情報を取得
        $asset = $this->ModelAssets->bySerialOrAssetNo($data['serial_no'], $data['asset_no']);

        // トランザクション開始
        $this->ModelPickingPlans->begin();

        try {
            // 出庫予定を保存
            $newPlan = $this->ModelPickingPlans->addNew($plan);
            $this->AppError->result($newPlan);

            $newPlanDetail = [];
            if (!$this->AppError->has()) {
                // 出庫予定詳細を保存
                $newPlanDetail = $this->ModelPickingPlanDetails->addNew($plan, $newPlan['data'], null);
                $this->AppError->result($newPlanDetail);
            }

            // 交換時
            if (!$this->AppError->has() && $plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.exchange')) { // 交換時
                // 交換情報作成（入庫予定含む）
                $newExchange = $this->ModelExchanges->addNew($data['exchange'], $asset, $newPlan['data']['id'], $newPlanDetail['data']['id']);
                $this->AppError->result($newExchange);
            }

            // 修理時
            if (!$this->AppError->has() && $plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.repair')) { // 交換時
                // 修理情報作成（入庫予定含む）
                $newRepair = $this->ModelRepairs->addNew($data['repair'], $asset, $newPlan['data']['id']);
                $this->AppError->result($newRepair);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelPickingPlans->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelPickingPlans->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelPickingPlans->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['plan' => $newPlan['data']]);
    }

    /**
     * 送信された出庫予定データを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('plan', ['post']);
        if (!$data) return;

        $plan = $data['plan'];

        // 資産情報を取得
        $asset = $this->ModelAssets->bySerialOrAssetNo($data['serial_no'], $data['asset_no']);

        // トランザクション開始
        $this->ModelPickingPlans->begin();

        try {
            // 出庫予定を保存
            $updatePlan = $this->ModelPickingPlans->save($plan);
            $this->AppError->result($updatePlan);

            if (!$this->AppError->has()) {
                // 出庫予定詳細を保存
                $updatePlanDetail = $this->ModelPickingPlanDetails->saveRequest($plan, $updatePlan['data']);
                $this->AppError->result($updatePlanDetail);
            }

            // 交換時
            if (!$this->AppError->has() && $plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.exchange')) { // 交換時
                // 交換情報作成（入庫予定含む）
                $updateExchange = $this->ModelExchanges->saveByPicking($data['exchange'], $asset);
                $this->AppError->result($updateExchange);
            }

            // 修理時
            if (!$this->AppError->has() && $plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.repair')) { // 交換時
                // 修理情報作成（入庫予定含む）
                $updateRepair = $this->ModelRepairs->saveByPicking($data['repair'], $asset);
                $this->AppError->result($updateRepair);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelPickingPlans->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelPickingPlans->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelPickingPlans->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['plan' => $updatePlan['data']]);
    }

    /**
     * 指定された出庫予定IDの出庫予定データを取消する
     *
     */
    public function cancel()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelPickingPlans->begin();

        try {
            // 出庫予定（+出庫予定詳細）のステータスを取消に更新する
            $deletePlan = $this->ModelPickingPlans->cancel($data);
            $this->AppError->result($deletePlan);

            // 出庫予定データ
            $plan = $this->ModelPickingPlans->get($data['id']);

            // 交換時
            if (!$this->AppError->has() && $$plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.exchange')) { // 交換時
                // 交換情報作成（入庫予定含む）
                //$updateExchange = $this->ModelExchanges->cancelByPicking();
                //$this->AppError->result($updateExchange);
            }

            // 修理時
            if (!$this->AppError->has() && $plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.repair')) { // 交換時
                // 修理情報作成（入庫予定含む）
                //$updateRepair = $this->ModelRepairs->cancelByPicking();
                //$this->AppError->result($updateRepair);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelPickingPlans->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelPickingPlans->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelPickingPlans->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['plan' => $deletePlan['data']]);
    }

    /**
     * 指定された出庫予定IDの取消状態の出庫予定データを取消解除する（未出庫に戻す）
     *
     */
    public function cancelRestore()
    {
        $data = $this->validateParameter('cancel', ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelPickingPlans->begin();

        try {
            // 出庫予定（+出庫予定詳細）のステータスを未出庫に更新する
            $updatePlan = $this->ModelPickingPlans->cancelRestore($data['cancel']);
            $this->AppError->result($updatePlan);

            // 出庫予定データ
            $plan = $this->ModelPickingPlans->get($data['id']);

            // 交換時
            if (!$this->AppError->has() && $$plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.exchange')) { // 交換時
                // 交換情報作成（入庫予定含む）
                //$updateExchange = $this->ModelExchanges->cancelRestoreByPicking();
                //$this->AppError->result($updateExchange);
            }

            // 修理時
            if (!$this->AppError->has() && $plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.repair')) { // 交換時
                // 修理情報作成（入庫予定含む）
                //$updateRepair = $this->ModelRepairs->cancelRestoreByPicking();
                //$this->AppError->result($updateRepair);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelPickingPlans->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelPickingPlans->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelPickingPlans->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['plan' => $updatePlan['data']]);
    }

    /**
     * 指定された出庫予定IDの取消状態の出庫予定データを取消確定にする
     *
     */
    public function cancelFix()
    {
        $data = $this->validateParameter('cancel', ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelPickingPlans->begin();

        try {
            // 出庫予定（+出庫予定詳細）のステータスを取消確定に更新する
            $updatePlan = $this->ModelPickingPlans->cancelFix($data['cancel']);
            $this->AppError->result($updatePlan);

            // 交換時
            if (!$this->AppError->has() && $$plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.exchange')) { // 交換時
                // 交換情報作成（入庫予定含む）
                //$updateExchange = $this->ModelExchanges->cancelFixByPicking();
                //$this->AppError->result($updateExchange);
            }

            // 修理時
            if (!$this->AppError->has() && $plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.repair')) { // 交換時
                // 修理情報作成（入庫予定含む）
                //$updateRepair = $this->ModelRepair->cancelFixByPicking();
                //$this->AppError->result($updateRepair);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelPickingPlans->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelPickingPlans->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelPickingPlans->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['plan' => $updatePlan['data']]);
    }

    /**
     * 指定された出庫予定が依頼時に編集可能かどうかを取得する
     *
     */
    public function validateRequestEdit()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        // 編集対象の予定が未出庫の状態かどうかをチェックする
        $validate = $this->ModelPickingPlans->validateRequestEdit($data['id']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }
}
