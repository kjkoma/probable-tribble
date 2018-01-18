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
 * Pickings API Controller
 *
 */
class ApiPickingsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelPickings');
        $this->_loadComponent('ModelPickingDetails');
        $this->_loadComponent('ModelPickingPlans');
        $this->_loadComponent('ModelPickingPlanDetails');
        $this->_loadComponent('ModelRepairs');
        $this->_loadComponent('ModelExchanges');
        $this->_loadComponent('ModelAssets');
        $this->_loadComponent('ModelStocks');
    }

    /**
     * 出庫情報を検索する
     *
     */
    public function search()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 出庫一覧を取得
        $pickings = $this->ModelPickingDetails->search($data['cond']);

        // 一覧表示用に編集する
        $list = []; $counter = 0; $limit = intVal(Configure::read('WNote.ListLimit.maxcount'));
        foreach($pickings as $picking) {
            $list[] = [
                'id'                  => $picking['picking']['id'],
                'picking_detail_id'   => $picking['id'],
                'picking_kbn'         => $picking['picking']['picking_kbn_name']['name'],
                'picking_date'        => $picking['picking']['picking_date'],
                'req_user_name'       => $picking['picking']['picking_plan']['picking_plan_req_user']['sname'] . ' ' . $picking['picking']['picking_plan']['picking_plan_req_user']['fname'],
                'use_user_name'       => $picking['picking']['picking_plan']['picking_plan_use_user']['sname'] . ' ' . $picking['picking']['picking_plan']['picking_plan_use_user']['fname'],
                'classification_name' => $picking['asset']['classification']['kname'],
                'maker_name'          => $picking['asset']['company']['kname'],
                'product_name'        => $picking['asset']['product']['kname'],
                'model_name'          => ($picking['asset']['product_model']) ? $picking['asset']['product_model']['kname'] : '',
                'serial_no'           => $picking['asset']['serial_no'],
                'asset_no'            => $picking['asset']['asset_no'],
                'voucher_no'          => $picking['picking']['voucher_no'],
                'instock_suser_name'  => $picking['picking']['picking_suser']['kname'],
                'confirm_suser_name'  => ($picking['picking']['picking_confirm_suser']) ? $picking['picking']['picking_confirm_suser']['kname'] : '',
            ];
            $counter++;
            if ($counter > $limit) break;  // 最大500件に制限する
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['pickings' => $list]);
    }


    /**
     * 送信された出庫予定詳細データを出庫登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter(['picking', 'plan_detail_id'], ['post']);
        if (!$data) return;

        // 出庫予定詳細取得
        $planDetail = $this->ModelPickingPlanDetails->get($data['plan_detail_id']);
        if (!$planDetail) {
            $this->setError('指定された出庫予定詳細が存在していません。', 'NOT_FOUND_PICKING_PLAN_DETAILS_EXCEPTION');
            return;
        }

        // 出庫予定取得
        $plan = $this->ModelPickingPlans->get($planDetail['picking_plan_id']);
        if (!$plan) {
            $this->setError('指定された出庫予定が存在していません。', 'NOT_FOUND_PICKING_PLAN_EXCEPTION');
            return;
        }

        // 資産情報を取得
        $asset = $this->ModelAssets->get($planDetail['asset_id']);
        if (!$asset) {
            $this->setError('資産情報が存在していません。', 'NOT_FOUND_PICKING_ASSET_EXCEPTION');
            return;
        }

        // トランザクション開始
        $this->ModelPickings->begin();

        try {
            // 出庫登録
            $newPicking = $this->ModelPickings->addNew($data['picking'], $plan, $planDetail, $asset);
            $this->AppError->result($newPicking);

            // 出庫詳細登録
            if (!$this->AppError->has()) {
                $newPickingDetail = $this->ModelPickingDetails->addNew($newPicking['data'], $asset);
                $this->AppError->result($newPickingDetail);
            }

            // 在庫更新
            if (!$this->AppError->has()) {
                $updateStock = $this->ModelStocks->updatePicking($newPicking['data'], $asset);
                $this->AppError->result($updateStock);
            }

            // 出庫予定詳細更新（＋出庫予定ステータス更新）
            if (!$this->AppError->has()) {
                $updatePlanDetail = $this->ModelPickingPlanDetails->updateComplete($newPicking['data']);
                $this->AppError->result($updatePlanDetail);
            }

            // 資産情報更新
            if (!$this->AppError->has()) {
                $updateAsset = $this->ModelAssets->updatePicking($asset['id'], $newPicking['data'], $plan, $updateStock['data']);
                $this->AppError->result($updateAsset);
            }

            // 交換時
            if (!$this->AppError->has() && $plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.exchange')) { // 交換時
                // 交換情報作成（入庫予定含む）
                $updateExchange = $this->ModelExchanges->updatePicking($planDetail, $newPickingDetail['data']);
                $this->AppError->result($updateExchange);
            }

            // 修理時
            if (!$this->AppError->has() && $plan['picking_kbn'] == Configure::read('WNote.DB.Picking.PickingKbn.repair')) { // 交換時
                // 修理情報作成（入庫予定含む）
                $updateRepair = $this->ModelRepairs->updatePicking($planDetail, $newPickingDetail['data']);
                $this->AppError->result($updateRepair);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelPickings->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelPickings->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelPickings->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['plan' => $newPlan['data']]);
    }
}
