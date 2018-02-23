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
 * Repairs API Controller
 *
 */
class ApiRepairsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelRepairs');
        $this->_loadComponent('ModelAssets');
    }

    /**
     * 修理IDより修理情報を取得する
     *
     */
    public function repair()
    {
        $data = $this->validateParameter('repair_id', ['post']);
        if (!$data) return;

        // 修理情報を取得
        $repair = $this->ModelRepairs->repair($data['repair_id']);

        // 表示用に編集する
        $repair['repair_sts_name']   = $repair['repair_st']['name'];
        $repair['sendback_kbn_name'] = $repair['repairs_sendback_kbn']['name'];
        $repair['datapick_kbn_name'] = $repair['repairs_datapick_kbn']['name'];
        $repair['trouble_kbn_name']  = $repair['repairs_trouble_kbn']['name'];
        $repair['serial_no']         = $repair['repair_asset']['serial_no'];
        $repair['asset_no']          = $repair['repair_asset']['asset_no'];
        $repair['asset_kname']       = $repair['repair_asset']['kname'];
        $repair['instock_date']      = ($repair['instock']) ? $repair['instock']['instock_date'] : '';
        $repair['picking_date']      = ($repair['picking']) ? $repair['picking']['picking_date'] : '';

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['repair' => $repair]);
    }

    /**
     * 資産IDより修理履歴一覧を取得する
     *
     */
    public function listByAssetId()
    {
        $data = $this->validateParameter('asset_id', ['post']);
        if (!$data) return;

        // 修理一覧を取得
        $repairs = $this->ModelRepairs->listByAssetId($data['asset_id']);

        // 一覧用に編集する
        foreach($repairs as $repair) {
            $repair['repair_kbn_name']  = $repair['repair_repair_kbn']['name'];
            $repair['repair_sts_name']  = $repair['repair_st']['name'];
            $repair['trouble_kbn_name'] = $repair['repairs_trouble_kbn']['name'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['repairs' => $repairs]);
    }

    /**
     * 修理一覧を表示する
     *
     */
    public function search()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 修理一覧を取得
        $repairs = $this->ModelRepairs->search($data['cond'], true);

        // 修理表示用に編集する
        $list = [];
        foreach($repairs as $repair) {
            $list[] = [
                'id'                  => $repair['id'],
                'repair_sts'          => $repair['repair_sts'],
                'repair_sts_name'     => $repair['repair_st']['name'],
                'req_date'            => $repair['picking_plan']['req_date'],
                'req_user_name'       => $repair['picking_plan']['picking_plan_req_user']['sname'] . ' ' . $repair['picking_plan']['picking_plan_req_user']['fname'],
                'category_name'       => $repair['repair_asset']['classification']['category']['kname'],
                'classification_name' => $repair['repair_asset']['classification']['kname'],
                'maker_name'          => $repair['repair_asset']['company']['kname'],
                'product_name'        => $repair['repair_asset']['product']['kname'],
                'asset_no'            => $repair['repair_asset']['asset_no'],
                'serial_no'           => $repair['repair_asset']['serial_no'],
                'trouble_kbn_name'    => $repair['repairs_trouble_kbn']['name']
            ];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['repairs' => $list]);
    }

    /**
     * 送信された修理データを登録する
     *
     */
    public function entry()
    {
        $data = $this->validateParameter(['repair', 'asset_id'], ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelRepairs->begin();

        try {
            // 修理データを保存
            $newRepair = $this->ModelRepairs->entry($data['repair'], $data['asset_id']);
            $this->AppError->result($newRepair);

            if (!$this->AppError->has()) {
                // 資産を修理中に更新
                $updateAsset = $this->ModelAssets->repair($data['asset_id']);
                $this->AppError->result($updateAsset);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelRepairs->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelRepairs->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelRepairs->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['repair' => $newRepair['data']]);
    }

    /**
     * 送信された修理データを完了に更新する
     *
     */
    public function complete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelRepairs->begin();

        try {
            // 修理データを更新
            $updateRepair = $this->ModelRepairs->entryComplete($data['id']);
            $this->AppError->result($updateRepair);

            if (!$this->AppError->has()) {
                // 資産を在庫に更新
                $updateAsset = $this->ModelAssets->repairComplete($updateRepair['data']['repair_asset_id']);
                $this->AppError->result($updateAsset);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelRepairs->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelRepairs->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelRepairs->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['repair' => $updateRepair['data']]);
    }

    /**
     * 送信された修理データを廃棄に更新する
     *
     */
    public function abrogate()
    {
        $data = $this->validateParameter(['id', 'abrogate_reason'], ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelRepairs->begin();

        try {
            // 修理データを更新
            $updateRepair = $this->ModelRepairs->entryAbrogate($data['id']);
            $this->AppError->result($updateRepair);

            if (!$this->AppError->has()) {
                // 資産を廃棄予定に更新
                $updateAsset = $this->ModelAssets->repairAbrogate($updateRepair['data']['repair_asset_id'], $data['abrogate_reason']);
                $this->AppError->result($updateAsset);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelRepairs->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelRepairs->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelRepairs->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['repair' => $updateRepair['data']]);
    }
}
