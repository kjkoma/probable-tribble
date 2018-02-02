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
namespace App\Controller\Api\Stocktake;

use \Exception;
use Cake\Core\Configure;
use App\Controller\Api\ApiController;

/**
 * StocktakeTargets API Controller
 *
 */
class ApiStocktakeTargetsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelStocktakeTargets');
        $this->_loadComponent('ModelAssets');
    }

    /**
     * シリアル番号、または、資産管理番号より在庫資産情報を取得する
     *
     */
    public function stockAssetBySerialOrAssetNo()
    {
        $data = $this->validateParameter(['serial_no', 'asset_no'], ['post']);
        if (!$data) return;

        // 資産を取得
        $asset = $this->ModelAssets->bySerialOrAssetNo($data['serial_no'], $data['asset_no']);
        if ($asset) {
            // 在庫を確認
            $stock = $this->ModelStocktakeTargets->findByAssetId($asset['id']);
        }

        if (!$asset || !$stock || count($stock) == 0) {
            $asset = [];
        } else {
            $asset = $this->ModelAssets->asset($asset['id']);
            $asset['classification_name'] = $asset['classification']['kname'];
            $asset['product_name']        = $asset['product']['kname'];
            $asset['product_model_name']  = $asset['product_model']['kname'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['asset' => $asset]);
    }

    /**
     * 数量管理在庫資産を検索する
     *
     */
    public function searchStockCountAssets()
    {
        $data = $this->validateParameter(['stocktake_id', 'cond'], ['post']);
        if (!$data) return;

        // 在庫一覧を取得
        $stocks = $this->ModelStocktakeTargets->stockCountAssets($data['stocktake_id'], $data['cond']);

        // 表示用に編集する
        $assets = [];
        foreach($stocks as $stock) {
            $asset = [];
            $asset['id']                  = $stock['asset_id'];
            $asset['category_name']       = $stock['asset']['classification']['category']['kname'];
            $asset['maker_name']          = $stock['asset']['company']['kname'];
            $asset['classification_id']   = $stock['asset']['classification_id'];
            $asset['classification_name'] = $stock['asset']['classification']['kname'];
            $asset['product_id']          = $stock['asset']['product_id'];
            $asset['product_name']        = $stock['asset']['product']['kname'];
            $asset['product_model_id']    = $stock['asset']['product_model_id'];
            $asset['product_model_name']  = $stock['asset']['product_model']['kname'];
            $asset['stock_count']         = $stock['stock_count'];
            $asset['stocktake_detail_id'] = ($stock['stocktake_detail']) ? $stock['stocktake_detail']['id'] : '';
            $asset['stocktake_count']     = ($stock['stocktake_detail']) ? $stock['stocktake_detail']['stocktake_count'] : '';
            $assets[] = $asset;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['assets' => $assets]);
    }

    /**
     * 棚卸数量差分の一覧を取得する（在庫が存在しない差分を除く差分）
     * 
     *
     */
    public function countUnmatches()
    {
        $data = $this->validateParameter(['stocktake_id'], ['post']);
        if (!$data) return;

        // 棚卸数量差分を取得
        $unmatches = $this->ModelStocktakeTargets->countUnmatches($data['stocktake_id']);

        // 一覧表示用に編集
        $list = [];
        foreach($unmatches as $unmatch) {
            $list[] = [
                'stocktake_id'        => $unmatch['stocktake_id'],
                'stocktake_target_id' => $unmatch['id'],
                'stocktake_detail_id' => $unmatch['stocktake_detail']['id'],
                'asset_id'            => $unmatch['asset_id'],
                'asset_type'          => $unmatch['asset']['asset_type'],
                'unmatch_kbn_name'    => ($unmatch['stocktake_detail']) ? $unmatch['stocktake_detail']['stocktake_unmatch_kbn_name']['name'] : '未実施',
                'classification_name' => $unmatch['asset']['classification']['kname'],
                'product_name'        => $unmatch['asset']['product']['kname'],
                'product_model_name'  => $unmatch['asset']['product_model']['kname'],
                'serial_no'           => ($unmatch['asset']) ? $unmatch['asset']['serial_no'] : $unmatch['stocktake_detail']['serial_no'],
                'asset_no'            => ($unmatch['asset']) ? $unmatch['asset']['asset_no'] : $unmatch['stocktake_detail']['asset_no'],
                'stocktake_count'     => $unmatch['stocktake_detail']['stocktake_count'],
                'stock_count'         => $unmatch['stock_count'],
                'current_stock_count' => $unmatch['stock']['stock_count']
            ];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['unmatches' => $list]);
    }
}
