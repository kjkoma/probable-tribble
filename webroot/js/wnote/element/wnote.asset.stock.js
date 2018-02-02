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
/*
 */
/** ---------------------------------------------------------------------------
 *  定数
 *  -------------------------------------------------------------------------*/
const WNOTE_ASSET_STOCK = {
    FORM_KEY : 'form-elem-assetview-stock',
    PREFIX   : 'elemAssetviewStock_'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Asset = WNote.Asset || {};
WNote.Asset.Stock = {};

/** datatableのインスタンス */
WNote.Asset.Stock.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 在庫変動履歴一覧テーブル（datatable）初期化
    WNote.Asset.Stock.datatable = $('#elemAssetStock-datatable').DataTable({
        paging    : false,
        scrollY   : 800,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'history_type_name', width: '10%' },
            { data: 'instock_date'     , width: '12%' },
            { data: 'picking_date'     , width: '12%' },
            { data: 'stocktake_date'   , width: '12%' },
            { data: 'change_at'        , width: '16%' },
            { data: 'stock_count_org'  , width: '9%'  },
            { data: 'stock_count'      , width: '9%'  },
            { data: 'reason_kbn_name'  , width: '20%' }
        ],
        data      : []
    });

    /* 表示資産変更イベント登録 */
    WNote.Asset.afterChangeAssetRegister('stock', WNote.Asset.Stock.show);

});

/** ---------------------------------------------------------------------------
 *  タブ選択時処理（wnote.asset.js宣言の実装）
 *  -------------------------------------------------------------------------*/
/**
 * タブ選択時処理
 */
WNote.Asset.selectStockHandler = function() {
}

/** ---------------------------------------------------------------------------
 *  在庫、在庫変動履歴表示処理
 *  -------------------------------------------------------------------------*/
/**
 * 在庫、在庫変動履歴を表示する
 */
WNote.Asset.Stock.show = function() {
    WNote.Asset.Stock.getStocks(WNote.Asset.selectData.id);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 在庫、在庫変動履歴一覧取得
 *
 * @param {string} assetId 資産ID
 */
WNote.Asset.Stock.getStocks = function(assetId) {
    WNote.ajaxFailureMessage = '在庫、在庫変動履歴の読込に失敗しました。';
    WNote.ajaxSendBasic('/api/stock/api-stocks/stock_and_histories', 'POST',
        { 'asset_id': assetId },
        false,
        WNote.Asset.Stock.getStocksSuccess
    );
}

/**
 * 在庫、在庫変動履歴一覧成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.Stock.getStocksSuccess = function(data) {

    // 在庫表示
    WNote.Form.clearTexts(WNOTE_ASSET_STOCK.FORM_KEY);
    WNote.Form.setTextsWithPrefix(data.data.param.stock, WNOTE_ASSET_STOCK.PREFIX);

    // 一覧表示
    if (data && data.data) {
        WNote.Asset.Stock.datatable
            .clear()
            .rows.add(data.data.param.histories)
            .draw();
    }
}
