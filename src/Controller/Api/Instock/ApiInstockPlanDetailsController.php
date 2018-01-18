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
 * Instock Plan Details API Controller
 *
 */
class ApiInstockPlanDetailsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelInstockPlanDetails');
        $this->_loadComponent('ModelInstockPlans');
        $this->_loadComponent('ModelInstocks');
        $this->_loadComponent('ModelAssets');
        $this->_loadComponent('ModelAssetBacks');
    }

    /**
     * 指定された入庫予定詳細IDの入庫予定詳細情報を取得する
     *
     */
    public function detail()
    {
        $data = $this->validateParameter('detail_id', ['post']);
        if (!$data) return;

        // 入庫予定詳細を取得
        $detail = $this->ModelInstockPlanDetails->detail($data['detail_id']);
        if (!$detail) {
            $this->setError('指定されたデータがありません。', 'NOT_FOUND_INSTOCK_PLAN', $data, 404);
            return;
        }
        // 各名称を付加（ビューのselect2選択BOX用）
        $detail['classification_text']   = $detail['classification']['kname'];
        $detail['product_text']          = $detail['product']['kname'];
        $detail['product_model_text']    = ($detail['product_model']) ? $detail['product_model']['kname'] : '';

        // 資産返却情報を取得
        $assetback = $this->ModelAssetBacks->byInstockPlanDetailId($data['detail_id']);
        $assetback = ($assetback) ? $assetback : [];
        // 各名称を付加（ビューのselect2選択BOX用）
        $assetback['req_organization_text'] = ($assetback['id']) ? $assetback['asset_backs_req_organization']['kname'] : '';
        $assetback['req_user_text']         = ($assetback['id']) ? $assetback['asset_backs_req_user']['sname'] . ' ' . $assetback['asset_backs_req_user']['fname'] : '';
        $assetback['rcv_suser_text']        = ($assetback['id']) ? $assetback['asset_backs_rcv_suser']['kname'] : '';

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['detail' => $detail, 'assetback' => $assetback]);
    }

    /**
     * 入庫予定詳細一覧を取得する
     *
     */
    public function details()
    {
        $data = $this->validateParameter('plan_id', ['post']);
        if (!$data) return;

        // 入庫予定詳細を取得
        $details = $this->ModelInstockPlanDetails->details($data['plan_id']);

        // 一覧テーブル用に編集
        foreach($details as $detail) {
            $detail['category_name']       = $detail['classification']['category']['kname'];
            $detail['classification_name'] = $detail['classification']['kname'];
            $detail['maker_name']          = $detail['product']['company']['kname'];
            $detail['product_name']        = $detail['product']['kname'];
            $detail['model_name']          = ($detail['product_model']) ? $detail['product_model']['kname'] : '';
            $detail['instock_count']       = ($detail['instocks']) ? $detail['instocks'][0]['sum_instock_count'] : '0';
            $detail['detail_sts_name']     = $detail['instock_plan_details_st']['name'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['details' => $details]);
    }

    /**
     * シリアル／資産管理番号指定で未入庫・一部入庫の入庫予定詳細一覧を取得する
     *
     */
    public function detailsAsset()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 入庫予定詳細を取得
        $details = $this->ModelInstockPlanDetails->detailsAsset($data['cond']);

        // 一覧テーブル用に編集
        foreach($details as $detail) {
            $detail['instock_kbn']          = $detail['instock_plan']['instock_kbn'];
            $detail['instock_kbn_name']     = $detail['instock_plan']['instock_plans_kbn']['name'];
            $detail['plan_date']            = $detail['instock_plan']['plan_date'];
            $detail['name']                 = $detail['instock_plan']['name'];
            $detail['serial_no']            = $detail['_matchingData']['Assets']['serial_no'];
            $detail['asset_no']             = $detail['_matchingData']['Assets']['asset_no'];
            $detail['classification_name']  = $detail['classification']['kname'];
            $detail['product_name']         = $detail['product']['kname'];
            $detail['remarks']              = $detail['instock_plan']['remarks'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['details' => $details]);
    }

    /**
     * 送信された入庫予定詳細データを登録する
     *
     */
    public function add()
    {
        $data = $this->validateParameter('detail', ['post']);
        if (!$data) return;

        // 入庫予定を取得
        $plan = $this->ModelInstockPlans->get($data['detail']['instock_plan_id']);
        if (!$plan || count($plan) == 0) {
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'NOT_FOUND_INSTOCK_PLAN_EXCEPTION', $e);
            return;
        }

        // 資産情報を取得
        $asset = null;
        if ($plan['instock_kbn'] == Configure::read('WNote.DB.Instock.InstockKbn.back')) {
            $asset = $this->_getAssetBySerialOrAssetNo(
                $data['back']['serial_no'], $data['back']['asset_no'],
                $data['detail']['product_id'], $data['detail']['product_model_id']
            );
            if (!$asset) {
                $this->setError('対象の資産が見つかりませんでした。管理者へお問い合わせください。', 'NOT_FOUND_ASSET_EXCEPTION', $e);
                return;
            }
        }

        // トランザクション開始
        $this->ModelInstockPlanDetails->begin();

        try {
            // 入庫予定詳細を保存
            $newDetail = $this->ModelInstockPlanDetails->addNew($data['detail'], $plan, $asset);
            $this->AppError->result($newDetail);

            // 資産返却を保存
            if (!$this->AppError->has() && $asset) {
                $newAssetBack = $this->ModelAssetBacks->addNew($data['back'], $newDetail['data'], $asset);
                $this->AppError->result($newAssetBack);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelInstockPlanDetails->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelInstockPlanDetails->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelInstockPlanDetails->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['detail' => $newDetail['data']]);
    }

    /**
     * 送信された入庫予定詳細データを更新する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('detail', ['post']);
        if (!$data) return;

        // 入庫予定を取得
        $plan = $this->ModelInstockPlans->get($data['detail']['instock_plan_id']);
        if (!$plan || count($plan) == 0) {
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'NOT_FOUND_INSTOCK_PLAN_EXCEPTION', $e);
            return;
        }

        // 資産情報を取得
        $asset = null;
        if ($plan['instock_kbn'] == Configure::read('WNote.DB.Instock.InstockKbn.back')) {
            $asset = $this->_getAssetBySerialOrAssetNo(
                $data['back']['serial_no'], $data['back']['asset_no'],
                $data['detail']['product_id'], $data['detail']['product_model_id']
            );
            if (!$asset) {
                $this->setError('対象の資産が見つかりませんでした。管理者へお問い合わせください。', 'NOT_FOUND_ASSET_EXCEPTION', $e);
                return;
            }
        }

        // トランザクション開始
        $this->ModelInstockPlanDetails->begin();

        try {
            // 入庫予定詳細を保存
            $updateDetail = $this->ModelInstockPlanDetails->update($data['detail'], $asset);
            $this->AppError->result($updateDetail);

             // 入庫予定のステータスを更新
            if (!$this->AppError->has()) {
                $this->ModelInstockPlans->updateStatus($data['detail']['instock_plan_id']);
                $this->AppError->result($updateDetail);
            }

            // 資産返却を保存
            if (!$this->AppError->has() && $plan['instock_kbn'] == Configure::read('WNote.DB.Instock.InstockKbn.back')) {
                $updateAssetBack = $this->ModelAssetBacks->update($data['back'], $updateDetail['data'], $asset);
                $this->AppError->result($updateAssetBack);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelInstockPlanDetails->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelInstockPlanDetails->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelInstockPlanDetails->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['detail' => $updateDetail['data']]);
    }

    /**
     * 指定された入庫予定詳細IDの入庫予定データを削除する
     *
     */
    public function delete()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        // 検証
        if (!$this->deleteValidate($data['id'])) {
            $this->setError('削除時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'VALIDATE_EXCEPTION', $e);
            return;
        }

        // トランザクション開始
        $this->ModelInstockPlanDetails->begin();

        try {
            // 入庫予定詳細を削除(TableのDependencyを利用して依存データを削除)
            $deleteDetail = $this->ModelInstockPlanDetails->delete($data['id']);
            $this->AppError->result($deleteDetail);

            // 資産返却を削除
            if (!$this->AppError->has()) {
                $updateAssetBack = $this->ModelAssetBacks->deleteByInstockPlanDetailId($data['id']);
                $this->AppError->result($updateAssetBack);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelInstockPlanDetails->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelInstockPlanDetails->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelInstockPlanDetails->rollback();
            $this->setError('削除時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['detail' => $deleteDetail['data']]);
    }


    /**************************************************************************/
    /** プライベートメソッド                                                  */
    /**************************************************************************/
    /**
     * シリアル番号、または、資産管理番号より資産情報（Asset）を取得する。
     * シリアル番号、資産管理番号が未指定の場合は、製品とモデルより数量管理の資産情報を取得する
     *
     * @param string $serialNo  シリアル番号
     * @param string $assetNo   資産管理番号
     * @param string $productId 製品ID
     * @param string $modelId   モデルID
     * @return \App\Model\Entity\Asset 資産情報
     */
    private function _getAssetBySerialOrAssetNo($serialNo, $assetNo, $productId, $modelId)
    {
        $isAsset = false;
        $isAsset = ($serialNo !== '' || $assetNo !== '');

        $asset = null;
        if ($isAsset) {
            $asset = $this->ModelAssets->bySerialOrAssetNo($serialNo, $assetNo, $productId, $modelId);
        } else {
            $asset = $this->ModelAssets->assetCountType($productId, $modelId);
        }

        return (!$asset || count($asset) == 0) ? null : $asset;
    }

    /**************************************************************************/
    /** 検証用メソッド                                                        */
    /**************************************************************************/
    /**
     * 入庫予定詳細データ削除時の検証を行う
     *
     * @param integer $detailId 入庫予定詳細ID
     */
    public function deleteValidate($detailId)
    {
        $validate = true;

        // 入庫済チェック
        if ($this->validateAlreadyInstock($detailId)) {
            $validate = false;
        }

        return $validate;
    }

    /**
     * 指定された入庫予定詳細と予定数量に対する入庫済数量の妥当性を検証する
     *
     */
    public function validatePlanCount()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        // 予定数量
        $planCount = (array_key_exists('plan_count', $data) && !empty($data['plan_count'])) ? intVal($data['plan_count']) : 0;

        // 入庫数量
        $instockCount = $this->ModelInstocks->instockCountByPlanDetailId($data['id']);


        $validate = ($planCount >= $instockCount) ? true : false;

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }

    /**
     * 指定された入庫予定詳細に入庫済データが存在するかどうかを検証する
     * 
     */
    public function validateAlreadyInstock()
    {
        $data = $this->validateParameter('id', ['post']);
        if (!$data) return;

        // 入庫数量を確認
        $instockCount = $this->ModelInstocks->instockCountByPlanDetailId($data['id']);
        $validate = ($instockCount == 0) ? false : true;

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }

}

