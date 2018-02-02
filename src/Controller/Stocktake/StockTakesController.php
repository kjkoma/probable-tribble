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
namespace App\Controller\StockTake;

use App\Controller\AppController;

/**
 * Stock Takes Controller
 *
 */
class StocktakesController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelStocktakes');
        $this->_loadComponent('ModelStocktakeTargets');
    }

    /**
     * 棚卸登録画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function entry()
    {
        $stocktakes = $this->ModelStocktakes->incompleteList();

        $this->set(compact('stocktakes'));
        $this->render();
    }

    /**
     * 棚卸実施画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function work()
    {
        $stocktakes = $this->ModelStocktakes->deadStockList();

        $this->set(compact('stocktakes'));
        $this->render();
    }

    /**
     * 棚卸一覧画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function search()
    {
        $this->render();
    }

    /**
     * 棚卸一覧データをエクスポートする
     *
     * @return \Cake\Http\Response|void
     */
    public function download()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 棚卸一覧を取得
        $stocktakes = $this->ModelStocktakeTargets->search($data['cond'], true);

        // ダウンロード
        $_serialize     = ['stocktakes'];
        $_extract   = [
            function($row) { return $row['stocktake']['stocktake_date']; },
            function($row) { return $row['stocktake']['stocktake_sts_name']['name']; },
            function($row) { return $row['stocktake']['stocktake_suser_name']['kname']; },
            function($row) { return $row['stocktake']['stocktake_confirm_suser_name']['kname']; },
            function($row) { return $row['stocktake']['start_date']; },
            function($row) { return $row['stocktake']['end_date']; },
            function($row) { return $row['stocktake']['stock_deadline_date']; },
            function($row) { return ($row['stocktake_detail']['serial_no']) ? $row['stocktake_detail']['serial_no'] : $row['asset']['serial_no']; },
            function($row) { return ($row['stocktake_detail']['asset_no']) ? $row['stocktake_detail']['asset_no'] : $row['asset']['asset_no']; },
            function($row) { return $row['asset']['classification']['kname']; },
            function($row) { return $row['asset']['product']['kname']; },
            function($row) { return $row['asset']['product_model']['kname']; },
            function($row) { return $row['stocktake_detail']['stocktake_count']; },
            function($row) { return $row['stock_count']; },
            function($row) { return ($row['stocktake_detail']) ? $row['stocktake_detail']['stocktake_unmatch_kbn_name']['name'] : '棚卸なし'; },
            function($row) { return ($row['stocktake_detail']) ? $row['stocktake_detail']['stocktake_kbn_name']['name'] : '未対応'; },
            function($row) { return $row['stocktake_detail']['correspond']; },
        ];
        $_header    = [
            '棚卸日', '状況', '担当者', '確認者', '開始日', '終了日', '在庫締日', 'シリアル番号', '資産管理番号',
            '分類', '製品名', 'モデル／型', '棚卸数量', '在庫数量', '差分区分', '対応区分', '対応理由'
        ];
        $_csvEncoding = 'SJIS-win';
        $_newline     = "\r\n";
        $_eol         = "\r\n";

        $this->response->download(urlencode('棚卸一覧ダウンロード.csv'));
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('stocktakes', '_serialize', '_extract', '_header', '_csvEncoding', '_newline', '_eol', '_bom'));
    }
}
