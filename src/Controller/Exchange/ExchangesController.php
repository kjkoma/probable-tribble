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
namespace App\Controller\Exchange;

use App\Controller\AppController;

/**
 * Exchanges Controller
 *
 */
class ExchangesController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelExchanges');
    }

    /**
     * 交換一覧を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function list()
    {
        $this->render();
    }

    /**
     * 交換一覧データをエクスポートする
     *
     * @return \Cake\Http\Response|void
     */
    public function downloadList()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 交換一覧を取得
        $exchanges = $this->ModelExchanges->search($data['cond'], true);

        // ダウンロード
        $_serialize = ['exchanges'];
        $_extract   = [
            function($row) { return $row['picking_plan']['req_date']; },
            function($row) { return $row['picking_plan']['picking_plan_req_user']['sname'] . ' ' . $row['picking_plan']['picking_plan_req_user']['fname']; },
            function($row) { return ($row['instock_id'] == '') ? '未入庫' : '入庫済'; },
            function($row) { return $row['exchanges_instock_asset']['product_id']; },
            function($row) { return $row['exchanges_instock_asset']['product']['kname']; },
            function($row) { return $row['exchanges_instock_asset']['asset_no']; },
            function($row) { return $row['exchanges_instock_asset']['serial_no']; },
            function($row) { return $row['exchanges_picking_asset']['product_id']; },
            function($row) { return $row['exchanges_picking_asset']['product']['kname']; },
            function($row) { return $row['exchanges_picking_asset']['asset_no']; },
            function($row) { return $row['exchanges_picking_asset']['serial_no']; },
        ];
        $_header    = [
            '依頼日', '依頼者', '入庫有無', '製品ID(入庫)', '製品名(入庫)', '資産管理番号(入庫)', 'シリアル番号(入庫)',
            '製品ID(出庫)', '製品名(出庫)', '資産管理番号(出庫)', 'シリアル番号(出庫)'
        ];
        $_csvEncoding = 'SJIS-win';
        $_newline     = "\r\n";
        $_eol         = "\r\n";

        $this->response->download(urlencode('交換一覧ダウンロード.csv'));
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('exchanges', '_serialize', '_extract', '_header', '_csvEncoding', '_newline', '_eol', '_bom'));
    }

}
