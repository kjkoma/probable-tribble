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

/**
 * Picking Plan Details API Controller
 *
 */
class ApiPickingPlanDetailsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelPickingPlanDetails');
        $this->_loadComponent('ModelPickingPlans');
        $this->_loadComponent('ModelAssets');
        $this->_loadComponent('SysModelSusers');
    }

    /**
     * 出庫予定と詳細情報を取得する
     *
     */
    public function plan()
    {
        $data = $this->validateParameter('detail_id', ['post']);
        if (!$data) return;

        // 出庫予定詳細を取得
        $detail = $this->ModelPickingPlanDetails->plan($data['detail_id']);

        // 表示用に編集する
        $detail['category_name']                         = $detail['category']['kname'];
        $detail['reuse_kbn_name']                        = $detail['picking_plan_detail_reuse_kbn']['name'];
        $detail['kitting_pattern_name']                  = $detail['kitting_pattern']['kname'];
        $detail['picking_plan']['picking_kbn_name']      = $detail['picking_plan']['picking_plan_picking_kbn']['name'];
        $detail['picking_plan']['plan_sts_name']         = $detail['picking_plan']['picking_plan_st']['name'];
        $detail['picking_plan']['req_organization_name'] = $detail['picking_plan']['picking_plan_req_organization']['kname'];
        $detail['picking_plan']['req_user_name']         = $detail['picking_plan']['picking_plan_req_user']['sname'] . ' ' . $detail['picking_plan']['picking_plan_req_user']['fname'];
        $detail['picking_plan']['use_organization_name'] = $detail['picking_plan']['picking_plan_use_organization']['kname'];
        $detail['picking_plan']['use_user_name']         = $detail['picking_plan']['picking_plan_use_user']['sname'] . ' ' . $detail['picking_plan']['picking_plan_use_user']['fname'];
        $detail['picking_plan']['dlv_organization_name'] = $detail['picking_plan']['picking_plan_dlv_organization']['kname'];
        $detail['picking_plan']['dlv_user_name']         = $detail['picking_plan']['picking_plan_dlv_user']['sname'] . ' ' . $detail['picking_plan']['picking_plan_dlv_user']['fname'];
        $detail['picking_plan']['rcv_suser_name']        = $detail['picking_plan']['picking_plan_rcv_suser']['kname'];
        $detail['picking_plan']['work_suser_name']       = $detail['picking_plan']['picking_plan_work_suser']['kname'];

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['detail' => $detail]);
    }

    /**
     * 出庫予定詳細検索を含む出庫予定一覧を取得する
     *
     */
    public function plans()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 出庫予定詳細を取得
        $plans = $this->_getPlans($data['cond']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['plans' => $plans]);
    }

    /**
     * (private)出庫予定詳細検索を含む出庫予定一覧を取得する
     * 
     * @param array $cond 検索条件
     */
    private function _getPlans($cond)
    {
        // 出庫予定詳細を取得
        $plans = $this->ModelPickingPlanDetails->plans($cond);

        // 一覧表示用に編集する
        foreach($plans as $plan) {
            $plan['plan_detail_id']         = $plan['id'];
            $plan['id']                     = $plan['picking_plan']['id'];
            $plan['plan_date']              = $plan['picking_plan']['plan_date'];
            $plan['req_date']               = $plan['picking_plan']['req_date'];
            $plan['plan_kbn']               = $plan['picking_plan']['plan_kbn'];
            $plan['plan_kbn_name']          = $plan['picking_plan']['picking_plan_picking_kbn']['name'];
            $plan['plan_sts']               = $plan['picking_plan']['plan_sts'];
            $plan['plan_sts_name']          = $plan['picking_plan']['picking_plan_st']['name'];
            $plan['arv_date']               = $plan['picking_plan']['arv_date'];
            $plan['req_user_name']          = $plan['picking_plan']['picking_plan_req_user']['sname'] . ' ' . $plan['picking_plan']['picking_plan_req_user']['fname'];
            $plan['rcv_suser_name']         = $plan['picking_plan']['picking_plan_rcv_suser']['kname'];
            $plan['work_suser_name']        = $plan['picking_plan']['picking_plan_work_suser']['kname'];
            $plan['category_name']          = $plan['category']['kname'];
            $plan['kitting_pattern_name']   = $plan['kitting_pattern']['kname'];
            $plan['serial_no']              = $plan['asset']['serial_no'];
            $plan['cancel_reason']          = $plan['picking_plan']['cancel_reason'];
        }

        return $plans;
    }


    /**
     * 出庫可能な出庫予定一覧を取得する
     *
     */
    public function plansPicking()
    {
        if (!$this->request->is('post')) {
            $this->setError('指定されたHTTPメソッドには対応していません。', 'UNSUPPORTED_HTTP_METHOD');
            return;
        }

        // 出庫予定詳細を取得
        $plans = $this->ModelPickingPlanDetails->plansPicking();

        // 一覧表示用に編集する
        foreach($plans as $plan) {
            $plan['plan_date']              = $plan['picking_plan']['plan_date'];
            $plan['req_date']               = $plan['picking_plan']['req_date'];
            $plan['picking_kbn_name']       = $plan['picking_plan']['picking_plan_picking_kbn']['name'];
            $plan['req_user_name']          = $plan['picking_plan']['picking_plan_req_user']['sname'] . ' ' . $plan['picking_plan']['picking_plan_req_user']['fname'];
            $plan['dlv_user_name']          = $plan['picking_plan']['picking_plan_dlv_user']['sname'] . ' ' . $plan['picking_plan']['picking_plan_dlv_user']['fname'];
            $plan['classification_name']    = $plan['classification']['kname'];
            $plan['product_name']           = $plan['product']['kname'];
            $plan['product_model_name']     = $plan['product_model']['kname'];
            $plan['serial_no']              = $plan['asset']['serial_no'];
            $plan['asset_no']               = $plan['asset']['asset_no'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['plans' => $plans]);
    }

    /**
     * 送信された出庫予定詳細データを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('entry', ['post']);
        if (!$data) return;

        $entry = $data['entry'];

        // 資産情報取得（資産タイプが資産の場合はシリアルで取得／数量の場合は製品とモデルより取得)
        $asset = null;
        if (trim($entry['serial_no']) !== '') {  // 資産管理品
            $asset = $this->ModelAssets->bySerialNo($entry['serial_no'], $entry['product_id'], $entry['product_model_id']);
        } else {
            $asset = $this->ModelAssets->assetCountType($entry['product_id'], $entry['product_model_id']);
        }
        if (count($asset) === 0) {
            $this->setResponseError('your request is failure.', ['message' => '資産が存在しないため、出庫予定詳細を更新することができません。']);
            return;
        }

        // トランザクション開始
        $this->ModelPickingPlanDetails->begin();

        try {
            // 出庫予定詳細を保存
            $updatePlanDetail = $this->ModelPickingPlanDetails->saveEntry($entry, $asset);
            $this->AppError->result($updatePlanDetail);

            $updatePlan = [];
            if (!$this->AppError->has()) {
                // 出庫予定を保存
                $updatePlan = $this->ModelPickingPlans->saveEntry($entry, $updatePlanDetail['data'], $asset);
                $this->AppError->result($updatePlan);
            }

            if (!$this->AppError->has()) {
                // 資産情報を保存
                $updateAsset = $this->ModelAssets->saveByPicking($asset['id'], $data['asset']['asset_no'], $data['asset']['remarks']);
                $this->AppError->result($updatePlan);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelPickingPlanDetails->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // 保存結果取得
            $plans = $this->_getPlans(['id' => $updatePlanDetail['data']['id']]);
            if (!$plans || count($plans) == 0) {
                // ロールバック
                $this->ModelPickingPlanDetails->rollback();
                $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_SAVERESULT_EXCEPTION', $e);
                return;
            }

            // コミット
            $this->ModelPickingPlanDetails->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelPickingPlanDetails->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', [ 'plan' => $plans->toArray()[0] ]);
    }

    /**
     * 送信された出庫予定詳細データを出庫登録する（ステータスを出庫前に更新する）
     *
     */
    public function addPicking()
    {
        $data = $this->validateParameter(['id'], ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelPickingPlanDetails->begin();

        try {
            // 出庫予定詳細（+出庫予定）のステータスを更新
            $updatePlanDetail = $this->ModelPickingPlanDetails->addPicking($data['id']);
            $this->AppError->result($updatePlanDetail);

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelPickingPlanDetails->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelPickingPlanDetails->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelPickingPlanDetails->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', [ 'detail' => $updatePlanDetail['data']]);
    }

    /**
     * 指定されたシリアルに対応する出庫可能な出庫予定詳細を取得する
     *
     */
    public function getEnablePickingPlan()
    {
        $data = $this->validateParameter('serial_no', ['post']);
        if (!$data) return;

        // 出庫予定詳細が出庫前の状態かどうかをチェックする
        $detail = $this->ModelPickingPlanDetails->getEnablePickingPlan($data['serial_no']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['detail' => $detail]);
    }

    /**
     * 指定されたシリアルに対応する出庫可能な出庫予定詳細IDが存在するかを検証する
     *
     */
    public function validateEnablePickingPlan()
    {
        $data = $this->validateParameter('serial_no', ['post']);
        if (!$data) return;

        // 出庫予定詳細が出庫前の状態かどうかをチェックする
        $detail = $this->ModelPickingPlanDetails->getEnablePickingPlan($data['serial_no']);
        $validate = (!$detail) ? false : true;

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }
}
