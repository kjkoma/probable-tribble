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
namespace App\Controller\Instock;

use App\Controller\AppController;

/**
 * Instocks Controller
 *
 */
class InstocksController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelInstockDetails');
        $this->_loadComponent('ModelCompanies');
    }

    /**
     * 入庫画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $delivers = $this->ModelCompanies->delivers();

        $this->set(compact('delivers'));
        $this->render();
    }

    /**
     * 入庫検索画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function search()
    {
        $makers = $this->ModelCompanies->makers();

        $this->set(compact('makers'));
        $this->render();
    }

    /**
     * 入庫一覧データをエクスポートする
     *
     * @return \Cake\Http\Response|void
     */
    public function download()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 入庫予定一覧を取得
        $instocks = $this->ModelInstockDetails->search($data['cond'], true);
$this->log($instocks);
        // ダウンロード
        $_serialize     = ['instocks'];
        $_extract   = [
            function($row) { return $row['instock']['instocks_instock_kbn']['name']; },
            function($row) { return $row['instock']['instock_plan']['plan_date']; },
            function($row) { return $row['instock']['instock_date']; },
            function($row) { return $row['instock']['instock_plan']['name']; },
            function($row) { return $row['instock']['voucher_no']; },
            function($row) { return $row['instock']['instock_count']; },
            function($row) { return $row['instock']['instock_suser']['kname']; },
            function($row) { return $row['instock']['confirm_suser']['kname']; },
            function($row) { return $row['asset']['classification']['kname']; },
            function($row) { return $row['asset']['company']['kname']; },
            function($row) { return $row['asset']['product']['kname']; },
            function($row) { return $row['asset']['product_model']['kname']; },
            function($row) { return $row['asset']['kname']; },
            function($row) { return $row['asset']['asset_no']; },
            function($row) { return $row['asset']['serial_no']; }
        ];
        $_header    = [
            '入庫区分', '予定日', '入庫日', '件名', '伝票番号', '入庫数', '入庫担当者', '入庫確認者', 
            '資産分類', 'メーカー', '製品', 'モデル(型)', '資産名', 'シリアル番号', '資産管理番号'
        ];
        $_csvEncoding = 'SJIS-win';
        $_newline     = "\r\n";
        $_eol         = "\r\n";

        $this->response->download(urlencode('入庫一覧ダウンロード.csv'));
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('instocks', '_serialize', '_extract', '_header', '_csvEncoding', '_newline', '_eol', '_bom'));
    }

}
