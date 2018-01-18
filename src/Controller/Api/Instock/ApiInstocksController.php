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
use Cake\Core\Configure;

/**
 * Instocks API Controller
 *
 */
class ApiInstocksController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelInstocks');
        $this->_loadComponent('ModelInstockDetails');
        $this->_loadComponent('ModelInstockPlans');
        $this->_loadComponent('ModelInstockPlanDetails');
        $this->_loadComponent('ModelAssets');
        $this->_loadComponent('ModelStocks');
        $this->_loadComponent('ModelRepairs');
        $this->_loadComponent('ModelExchanges');
        $this->_loadComponent('ModelAssetBacks');
    }

    /**
     * 指定された入庫IDの入庫情報を取得する
     *
     */
    public function instock()
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
     * 入庫情報を検索する
     *
     */
    public function search()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 入庫一覧を取得
        $instocks = $this->ModelInstockDetails->search($data['cond']);

        // 一覧表示用に編集する
        $list = []; $counter = 0; $limit = intVal(Configure::read('WNote.ListLimit.maxcount'));
        foreach($instocks as $instock) {
            $list[] = [
                'instock_detail_id'   => $instock['id'],
                'instock_kbn'         => $instock['instock']['instocks_instock_kbn']['name'],
                'classification_name' => $instock['asset']['classification']['kname'],
                'maker_name'          => $instock['asset']['company']['kname'],
                'product_name'        => $instock['asset']['product']['kname'],
                'model_name'          => ($instock['asset']['product_model']) ? $instock['asset']['product_model']['kname'] : '',
                'instock_date'        => $instock['instock']['instock_date'],
                'instock_count'       => $instock['instock']['instock_count'],
                'voucher_no'          => $instock['instock']['voucher_no'],
                'instock_suser_name'  => $instock['instock']['instock_suser']['kname'],
                'confirm_suser_name'  => ($instock['instock']['confirm_suser']) ? $instock['instock']['confirm_suser']['kname'] : '',
            ];
            $counter++;
            if ($counter > $limit) break;  // 最大500件に制限する
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['instocks' => $list]);
    }

    /**
     * 入庫予定一覧(新規のみ)を取得する
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
            $plan['plan_sts_name'] = $plan['instock_plans_st']['name'];
            $list[] = $plan;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['plans' => $list]);
    }

    /**
     * 送信された入庫データ（新規）を登録する
     *
     */
    public function addNew()
    {
        $data = $this->validateParameter(['instock'], ['post']);
        if (!$data) return;

        // 入庫予定取得
        $plan = $this->ModelInstockPlans->get($data['instock']['instock_plan_id']);
        if (!$plan) {
            $this->setError('指定された入庫予定が存在していません。', 'NOT_FOUND_INSTOCK_PLANS_EXCEPTION');
            return;
        }
        $plan = $plan->toArray();

        // 入庫予定詳細取得
        $planDetail = $this->ModelInstockPlanDetails->get($data['instock']['instock_plan_detail_id']);
        if (!$planDetail) {
            $this->setError('指定された入庫予定詳細が存在していません。', 'NOT_FOUND_INSTOCK_PLAN_DETAILS_EXCEPTION');
            return;
        }
        $planDetail = $planDetail->toArray();

        // トランザクション開始
        $this->ModelInstocks->begin();

        try {
            // 入庫登録
            $newInstock = $this->ModelInstocks->addNew($data['instock'], $data['serials'], $data['input_count'], $data['asset_id'], $plan, $planDetail);
            $this->AppError->result($newInstock);

            // 資産登録・更新
            if (!$this->AppError->has()) {
                $newAsset = $this->ModelAssets->instock($data['asset_id'], $newInstock['data'], $planDetail, $data['serials']);
                $this->AppError->result($newAsset);
            }

            // 在庫登録・更新
            if (!$this->AppError->has()) {
                $newStock = $this->ModelStocks->instock($data['asset_id'], $newAsset['data'], $newInstock['data'], $planDetail);
                $this->AppError->result($newStock);
            }

            // 入庫詳細登録
            if (!$this->AppError->has()) {
                $newInstockDetail = $this->ModelInstockDetails->addNew($newInstock['data'], $newAsset['data']);
                $this->AppError->result($newInstockDetail);
            }

            // 入庫予定詳細更新（＋入庫予定ステータス更新）
            if (!$this->AppError->has()) {
                $newInstockPlanDetail = $this->ModelInstockPlanDetails->updateInstock($newInstock['data']);
                $this->AppError->result($newInstockPlanDetail);
            }

            // 修理時：修理情報更新
            if (!$this->AppError->has() && $plan['instock_kbn'] == Configure::read('WNote.DB.Instock.InstockKbn.repair')) {
                $updateRepair = $this->ModelRepairs->updateInstock($planDetail['id'], $newInstock['data']['id']);
                $this->AppError->result($updateRepair);
            }

            // 交換時：交換情報更新
            if (!$this->AppError->has() && $plan['instock_kbn'] == Configure::read('WNote.DB.Instock.InstockKbn.exchange')) {
                $updateExchange = $this->ModelExchanges->updateInstock($planDetail['id'], $newInstock['data']['id']);
                $this->AppError->result($updateExchange);
            }

            // 返却時：返却情報更新
            if (!$this->AppError->has() && $plan['instock_kbn'] == Configure::read('WNote.DB.Instock.InstockKbn.back')) {
                $updateAssetBack = $this->ModelAssetBacks->updateInstock($planDetail['id'], $newInstock['data']['id']);
                $this->AppError->result($updateAssetBack);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelInstocks->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelInstocks->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelInstocks->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['instock' => $newInstock['data']]);
    }
}
