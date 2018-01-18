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
namespace App\Controller\Stock;

use App\Controller\AppController;

/**
 * Stocks Controller
 *
 */
class StocksController extends AppController
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
        $this->_loadComponent('ModelCompanies');
        $this->_loadComponent('ModelCategories');
        $this->_loadComponent('SysModelSnames');
    }

    /**
     * 在庫一覧を検索する
     *
     * @return \Cake\Http\Response|void
     */
    public function search()
    {
        $makers      = $this->ModelCompanies->makers();
        $categories  = $this->ModelCategories->valid();
        $assetType   = $this->SysModelSnames->byKey('ASSET_TYPE');
        $assetSts    = $this->SysModelSnames->byKey('ASSET_STS');
        $assetSubSts = $this->SysModelSnames->byKey('ASSET_SUB_STS');

        $this->set(compact('makers', 'categories', 'assetType', 'assetSts', 'assetSubSts'));
        $this->render();
    }

    /**
     * 在庫集計画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function summary()
    {
        $makers      = $this->ModelCompanies->makers();
        $assetSts    = $this->SysModelSnames->byKey('ASSET_STS');
        $assetSubSts = $this->SysModelSnames->byKey('ASSET_SUB_STS');

        $this->set(compact('makers', 'assetSts', 'assetSubSts'));
        $this->render();
    }

    /**
     * 在庫一覧データをエクスポートする
     *
     * @return \Cake\Http\Response|void
     */
    public function download()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 在庫一覧を取得
        $stocks = $this->ModelAssets->searchStock($data['cond'], true);

        // ダウンロード
        $_serialize     = ['stocks'];
        $_extract   = [
            function ($row) { return $row['asset_type_name']['name']; },             // 資産タイプ名称
            function ($row) { return $row['asset_sts_name']['name']; },              // 資産状況
            function ($row) { return $row['asset_sub_sts_name']['name']; },          // 資産状況（サブ）
            function ($row) { return $row['classification']['category']['kname']; }, // カテゴリ名
            function ($row) { return $row['classification']['kname']; },             // 分類名
            function ($row) { return $row['company']['kname']; },                    // メーカー名
            function ($row) { return $row['product']['kname']; },                    // 製品名
            function ($row) { return ($row['product_model']) ? $row['product_model']['kname'] : ''; }, // モデル(型)名
            function ($row) { return $row['serial_no']; },                           // シリアル番号
            function ($row) { return $row['asset_no']; },                            // 資産管理番号
            function ($row) { return $row['first_instock_date']; },                  // 初回入庫日
            function ($row) { return $row['account_date']; },                        // 計上日(初回出庫日)
            function ($row) { return $row['abrogate_date']; },                       // 廃棄日
            function ($row) { return $row['support_limit_date']; },                  // 保守期限日
            function ($row) { return $row['stock']['stock_count']; },                // 在庫数
            function ($row) { return $row['remarks']; },                             // 補足
            function ($row) { return $row['created_at']; },                          // 登録日時
            function ($row) { return $row['asset_created_suser']['kname']; },        // 登録者
            function ($row) { return $row['modified_at']; },                         // 更新日時
            function ($row) { return $row['asset_modified_suser']['kname']; },       // 更新者
        ];
        $_header    = [
            '資産タイプ', '資産状況', '資産状況(サブ)', 'カテゴリ名', '分類名', 'メーカー名', '製品名', 'モデル(型)名',
            'シリアル番号', '資産管理番号', '初回入庫日', '計上日(初回出庫日)', '廃棄日', '保守期限日', '在庫数',
            '補足', '登録日時', '登録者', '更新日時', '更新者'
        ];
        $_csvEncoding = 'SJIS-win';
        $_newline     = "\r\n";
        $_eol         = "\r\n";

        $this->response->download(urlencode('在庫ダウンロード.csv'));
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('stocks', '_serialize', '_extract', '_header', '_csvEncoding', '_newline', '_eol', '_bom'));
    }

    /**
     * 在庫集計データをエクスポートする
     *
     * @return \Cake\Http\Response|void
     */
    public function downloadSummary()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 在庫集計を取得
        $stocks = $this->ModelStocks->summary($data['cond'], true);

        // ダウンロード
        $_serialize     = ['stocks'];
        $_extract   = [
            'category_id', 'category_name', 'classification_id', 'classification_name', 'maker_id', 'maker_name',
            'product_id', 'product_name', 'product_model_id', 'product_model_name', 'asset_sts_name', 'asset_sub_sts_name', 'sum_stock_count'
        ];
        $_header    = [
            'カテゴリID', 'カテゴリ名', '分類ID', '分類名', 'メーカーID', 'メーカー名',
            '製品ID', '製品名', 'モデル(型)ID', 'モデル(型)名', '資産状況', '資産状況(サブ)', '在庫数'
        ];
        $_csvEncoding = 'SJIS-win';
        $_newline     = "\r\n";
        $_eol         = "\r\n";

        $this->response->download(urlencode('在庫集計ダウンロード.csv'));
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('stocks', '_serialize', '_extract', '_header', '_csvEncoding', '_newline', '_eol', '_bom'));
    }

}
