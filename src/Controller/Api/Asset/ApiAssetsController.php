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
namespace App\Controller\Api\Asset;

use \Exception;
use App\Controller\Api\ApiController;
use Cake\Core\Configure;

/**
 * Assets API Controller
 *
 */
class ApiAssetsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelAssets');
        $this->_loadComponent('ModelAssetAttributes');
        $this->_loadComponent('ModelStocks');
    }

    /**
     * 資産情報を取得する
     *
     */
    public function asset()
    {
        $data = $this->validateParameter('asset_id', ['post']);
        if (!$data) return;

        // 資産情報を取得
        $asset = $this->ModelAssets->asset($data['asset_id']);

        // 表示用に編集する
        $asset['asset_type_name']     = $asset['asset_type_name']['name'];
        $asset['category_name']       = $asset['classification']['category']['kname'];
        $asset['classification_name'] = $asset['classification']['kname'];
        $asset['classification_text'] = $asset['classification_name'];
        $asset['maker_name']          = $asset['company']['kname'];
        $asset['product_name']        = $asset['product']['kname'];
        $asset['product_text']        = $asset['product_name'];
        $asset['product_model_name']  = ($asset['product_model']) ? $asset['product_model']['kname'] : '';
        $asset['product_model_text']  = $asset['product_model_name'];
        $asset['asset_sts_name']      = $asset['asset_sts_name']['name'];
        $asset['asset_sub_sts_name']  = $asset['asset_sub_sts_name']['name'];
        $asset['abrogate_suser_name'] = $asset['asset_abrogate_suser']['kname'];
        $asset['created_user_name']   = $asset['asset_created_suser']['kname'];
        $asset['modified_user_name']  = $asset['asset_modified_suser']['kname'];

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['asset' => $asset]);
    }

    /**
     * 資産情報を検索する
     *
     */
    public function search()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 資産一覧を取得
        $assets = $this->ModelAssets->search($data['cond']);

        // 一覧表示用に編集する
        $list = []; $counter = 0; $limit = intVal(Configure::read('WNote.ListLimit.maxcount'));
        foreach($assets as $asset) {
            $list[] = [
                'id'                  => $asset['id'],
                'asset_type_name'     => $asset['asset_type_name']['name'],
                'asset_sts_name'      => $asset['asset_sts_name']['name'],
                'asset_sub_sts_name'  => $asset['asset_sub_sts_name']['name'],
                'kname'               => $asset['kname'],
                'classification_name' => $asset['classification']['kname'],
                'maker_name'          => $asset['company']['kname'],
                'product_name'        => $asset['product']['kname'],
                'serial_no'           => $asset['serial_no'],
                'asset_no'            => $asset['asset_no'],
                'current_user_name'   => ($asset['user']) ? $asset['user']['sname'] . ' ' . $asset['user']['fname'] : '',
            ];
            $counter++;
            if ($counter > $limit) break;  // 最大500件に制限する
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['assets' => $list]);
    }

    /**
     * 廃棄予定の資産情報を検索する
     *
     */
    public function abrogatePlans()
    {
        if (!$this->request->is('post')) {
            $this->setError('指定されたHTTPメソッドには対応していません。', 'UNSUPPORTED_HTTP_METHOD', $data);
            return;
        }

        // 廃棄予定一覧を取得
        $assets = $this->ModelAssets->abrogatePlans();

        // 一覧表示用に編集する
        $list = $this->_createAbrogateListData($assets);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['assets' => $list]);
    }

    /**
     * 廃棄済の資産情報を検索する
     *
     */
    public function searchAbrogates()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 廃棄済一覧を取得
        $assets = $this->ModelAssets->abrogates($data['cond']);

        // 一覧表示用に編集する
        $list = $this->_createAbrogateListData($assets);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['assets' => $list]);
    }

    /**
     * (プライベート)廃棄予定・廃棄済一覧用データの編集を行う
     *
     * @param array $assets 廃棄予定 or 廃棄済データ
     * @return array 廃棄予定 or 廃棄済の一覧用データ
     */
    private function _createAbrogateListData($assets)
    {
        $list = [];
        foreach($assets as $asset) {
            $list[] = [
                'id'                  => $asset['id'],
                'classification_name' => $asset['classification']['kname'],
                'maker_name'          => $asset['company']['kname'],
                'product_name'        => $asset['product']['kname'],
                'serial_no'           => $asset['serial_no'],
                'asset_no'            => $asset['asset_no'],
                'repair_count'        => $asset['repairs'][0]['repair_count'],
                'abrogate_date'       => $asset['abrogate_date'],
                'abrogate_suser_name' => $asset['asset_abrogate_suser']['kname'],
                'abrogate_reason'     => $asset['abrogate_reason']
            ];
        }

        return $list;
    }

    /**
     * 送信された資産データを追加する(資産属性含む)
     *
     */
    public function addWithAttr()
    {
        $data = $this->validateParameter(['asset', 'attr'], ['post']);
        if (!$data) return;

        // トランザクション開始
        $this->ModelAssets->begin();

        try {
            // 資産を保存
            $newAsset = $this->ModelAssets->addEntry($data['asset']);
            $this->AppError->result($newAsset);

            // 資産属性を保存
            if (!$this->AppError->has()) {
                $newAttribute = $this->ModelAssetAttributes->addEntry($newAsset['data'], $data['attr']);
                $this->AppError->result($newAttribute);
            }

            // 在庫を追加
            if (!$this->AppError->has()) {
                $newStock = $this->ModelStocks->addEntry($newAsset['data']);
                $this->AppError->result($newStock);
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelAssets->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelAssets->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelAssets->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['asset' => $newAsset['data']]);
    }


    /**
     * 送信された資産データを編集する
     *
     */
    public function edit()
    {
        $data = $this->validateParameter('asset', ['post']);
        if (!$data) return;

        try {
            // 資産を保存
            $updateAsset = $this->ModelAssets->editEntry($data['asset']);
            $this->AppError->result($updateAsset);

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
        $this->setResponse(true, 'your request is success', ['asset' => $updateAsset['data']]);
    }

    /**
     * 送信された廃棄予定データを廃棄する
     *
     */
    public function abrogate()
    {
        $data = $this->validateParameter('assets', ['post']);
        if (!$data) return;

        $assets = $data['assets'];

        // トランザクション開始
        $this->ModelAssets->begin();

        try {
            foreach($assets as $asset) {

                // 資産を廃棄済に更新
                $updateAsset = $this->ModelAssets->abrogate($asset['id']);
                $this->AppError->result($updateAsset);

                // 在庫を0に更新
                if (!$this->AppError->has()) {
                    $updateStock = $this->ModelStocks->abrogate($asset['id']);
                    $this->AppError->result($updateStock);
                }

                if ($this->AppError->has()) {
                    break;
                }
            }

            // エラー判定
            if ($this->AppError->has()) {
                // ロールバック
                $this->ModelAssets->rollback();
                $this->setResponseError('your request is failure.', $this->AppError->errors());
                return;
            }

            // コミット
            $this->ModelAssets->commit();

        } catch(Exception $e) {
            // ロールバック
            $this->ModelAssets->rollback();
            $this->setError('保存時に予期せぬエラーが発生しました。管理者へお問い合わせください。', 'UNEXPECTED_EXCEPTION', $e);
            return;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['asset' => $data['assets']]);
    }


    /**************************************************************************/
    /** 検証用メソッド                                                        */
    /**************************************************************************/
    /**
     * 指定されたシリアル番号の存在有無を検証する
     *
     */
    public function validateSerialNo()
    {
        $data = $this->validateParameter('serial_no', ['post']);
        if (!$data) return;

        $validate = true;

        // 資産
        $asset = $this->ModelAssets->bySerialNo($data['serial_no']);
        if (!$asset || count($asset) == 0) {
            $validate = false;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }

    /**
     * 指定された資産管理番号の存在有無を検証する
     *
     */
    public function validateAssetNo()
    {
        $data = $this->validateParameter('asset_no', ['post']);
        if (!$data) return;

        $validate = true;

        // 資産
        $asset = $this->ModelAssets->byAssetNo($data['asset_no']);
        if (!$asset || count($asset) == 0) {
            $validate = false;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }

    /**
     * 指定されたシリアル番号の重複を検証する(製品・モデル単位)
     *
     */
    public function validateDuplicateSerialNo()
    {
        $data = $this->validateParameter(['product_id', 'product_model_id', 'serial_no'], ['post']);
        if (!$data) return;

        $validate = true;

        // 重複チェック
        $asset = $this->ModelAssets->bySerialNo($data['serial_no'], $data['product_id'], $data['product_model_id']);
        if ($asset && count($asset) > 0) {
            $validate = false;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }

    /**
     * 指定された数量管理資産の重複を検証する
     *
     */
    public function validateDuplicateCountAsset()
    {
        $data = $this->validateParameter(['product_id', 'product_model_id'], ['post']);
        if (!$data) return;

        $validate = true;

        // 重複チェック
        $asset = $this->ModelAssets->assetCountType($data['product_id'], $data['product_model_id']);
        if ($asset && count($asset) > 0) {
            $validate = false;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }
}

