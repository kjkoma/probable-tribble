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
namespace App\Controller\Component;

use Cake\Core\Configure;

/**
 * 在庫履歴（StockHistories）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelStockHistoriesComponent extends AppModelComponent
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'StockHistories';
        parent::initialize($config);
    }

    /**
     * 指定資産の在庫変動履歴を取得する
     *  
     * - - -
     * @param array $assetID 資産ID
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 在庫変動履歴一覧（ResultSet or Array）
     */
    public function histories($assetId, $toArray = false)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'asset_id' => $assetId
            ])
            ->contain([
                'StockHistoriesHistTypeName' => function($q) {
                    return $q->select(['nkey', 'nid', 'name']);
                }
            ])
            ->contain([
                'StockHistoriesReasonKbnName' => function($q) {
                    return $q->select(['nkey', 'nid', 'name']);
                }
            ])
            ->contain([
                'Instocks' => function($q) {
                    return $q->select(['id', 'instock_date']);
                }
            ])
            ->contain([
                'Pickings' => function($q) {
                    return $q->select(['id', 'picking_date']);
                }
            ])
            ->contain([
                'Stocktakes' => function($q) {
                    return $q->select(['id', 'stocktake_date']);
                }
            ]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 画面登録による在庫履歴を登録する
     *  
     * - - -
     * 
     * @param array $stock   在庫情報
     * @param array $assetID 資産ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addEntry($stock, $assetId)
    {
        $history = [];
        $history['domain_id']       = $stock['domain_id'];
        $history['asset_id']        = $assetId;
        $history['history_type']    = Configure::read('WNote.DB.HistType.entry');
        $history['change_at']       = $this->now();
        $history['stock_count_org'] = $stock['stock_count_org'];
        $history['stock_count']     = $stock['stock_count'];
        $history['reason_kbn']      = Configure::read('WNote.DB.ReasonKbn.entry');

        return parent::add($history);
    }

    /**
     * 入庫による在庫履歴を登録する
     *  
     * - - -
     * 
     * @param array $stock   在庫情報
     * @param array $instock 入庫情報
     * @param array $assetID 資産ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addInstock($stock, $instock, $assetId)
    {
        $history = [];
        $history['domain_id']       = $stock['domain_id'];
        $history['asset_id']        = $assetId;
        $history['history_type']    = Configure::read('WNote.DB.HistType.instock');
        $history['instock_id']      = $instock['id'];
        $history['change_at']       = $instock['created_at'];
        $history['stock_count_org'] = $stock['stock_count_org'];
        $history['stock_count']     = $stock['stock_count'];
        $history['reason_kbn']      = Configure::read('WNote.DB.ReasonKbn.instock');

        return parent::add($history);
    }

    /**
     * 出庫による在庫履歴を登録する
     *  
     * - - -
     * 
     * @param array $stock 在庫情報
     * @param array $picking 出庫情報
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addPicking($stock, $picking, $asset)
    {
        $history = [];
        $history['domain_id']       = $stock['domain_id'];
        $history['asset_id']        = $asset['id'];
        $history['history_type']    = Configure::read('WNote.DB.HistType.picking');
        $history['picking_id']      = $picking['id'];
        $history['change_at']       = $picking['created_at'];
        $history['stock_count_org'] = $stock['stock_count_org'];
        $history['stock_count']     = $stock['stock_count'];
        $history['reason_kbn']      = Configure::read('WNote.DB.ReasonKbn.picking');

        return parent::add($history);
    }

    /**
     * 棚卸による在庫履歴を登録する
     *  
     * - - -
     * 
     * @param array $stock     在庫情報
     * @param array $stocktake 棚卸情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addStocktake($stock, $stocktake)
    {
        $history = [];
        $history['domain_id']       = $stock['domain_id'];
        $history['asset_id']        = $stock['asset_id'];
        $history['history_type']    = Configure::read('WNote.DB.HistType.stocktake');
        $history['stocktake_id']    = $stocktake['stocktake_id'];
        $history['change_at']       = $stock['modified_at'];
        $history['stock_count_org'] = $stock['stock_count_org'];
        $history['stock_count']     = $stock['stock_count'];
        $history['reason_kbn']      = Configure::read('WNote.DB.ReasonKbn.stocktake');

        return parent::add($history);
    }
}
