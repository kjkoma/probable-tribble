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
namespace App\Controller\Api\Stock;

use \Exception;
use App\Controller\Api\ApiController;
use Cake\Core\Configure;

/**
 * Stocks API Controller
 *
 */
class ApiStocksController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelStocks');
        $this->_loadComponent('ModelAssets');
    }

    /**
     * 在庫情報を検索する
     *
     */
    public function search()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 在庫一覧を取得(※資産ベースで取得する)
        $stocks = $this->ModelAssets->searchStock($data['cond']);

        // 一覧表示用に編集する
        $list = []; $counter = 0; $limit = intVal(Configure::read('WNote.ListLimit.maxcount'));
        foreach($stocks as $stock) {
            $list[] = [
                'id'                  => $stock['stock']['id'],
                'asset_id'            => $stock['id'],
                'asset_type_name'     => $stock['asset_type_name']['name'],
                'asset_sts_name'      => $stock['asset_sts_name']['name'],
                'asset_sub_sts_name'  => $stock['asset_sub_sts_name']['name'],
                'kname'               => $stock['kname'],
                'classification_name' => $stock['classification']['kname'],
                'maker_name'          => $stock['company']['kname'],
                'product_name'        => $stock['product']['kname'],
                'serial_no'           => $stock['serial_no'],
                'asset_no'            => $stock['asset_no'],
                'stock_count'         => $stock['stock']['stock_count']
            ];
            $counter++;
            if ($counter > $limit) break;  // 最大500件に制限する
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['stocks' => $list]);
    }

    /**
     * 在庫集計を取得する
     *
     */
    public function summary()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 在庫集計を取得
        $stocks = $this->ModelStocks->summary($data['cond']);

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['stocks' => $stocks]);
    }

    /**************************************************************************/
    /** 検証用メソッド                                                        */
    /**************************************************************************/
    /**
     * 指定されたシリアル番号の在庫有無（資産自体の存在含む）を検証する
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

        // 在庫
        if ($validate) {
            $stock = $this->ModelStocks->stock($asset['id']);
            if (!$stock || count($stock) == 0 || intVal($stock['stock_count']) < 1) {
                $validate = false;
            }
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }

    /**
     * 指定された資産管理番号の在庫有無（資産自体の存在含む）を検証する
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

        // 在庫
        if ($validate) {
            $stock = $this->ModelStocks->stock($asset['id']);
            if (!$stock || count($stock) == 0 || intVal($stock['stock_count']) < 1) {
                $validate = false;
            }
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }
}