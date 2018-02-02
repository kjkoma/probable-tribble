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
const WNOTE_STOCKTAKE_SUMMARY = {
    FORM_KEY : 'form-elem_stocktake_summary',
    PREFIX   : 'elemStocktakeSummary_',
    TEXT     : {
        AREA1: {
            STOCK_COUNT      : '#elemStocktakeSummary_area1_stock_count',
            STOCK_TAKE_COUNT : '#elemStocktakeSummary_area1_stocktake_count',
        },
        AREA2: {
            STOCK_COUNT      : '#elemStocktakeSummary_area2_stock_count',
            STOCK_TAKE_COUNT : '#elemStocktakeSummary_area3_stocktake_count',
        },
        AREA3: {
            COUNT            : '#elemStocktakeSummary_area3_count'
        },
        AREA4: {
            COUNT            : '#elemStocktakeSummary_area4_count'
        }
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Stocktake = WNote.Stocktake || {};
WNote.Stocktake.Summary = {};

/** 選択データ */
WNote.Stocktake.Summary.selectData = {
    stocktake : {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {
});

/** ---------------------------------------------------------------------------
 *  タブ選択時処理（wnote.stocktake.js宣言の実装）
 *  -------------------------------------------------------------------------*/
WNote.Stocktake.selectSummaryHandler = function() {
    // 棚卸情報を表示する
    WNote.Stocktake.Summary.showSummary(WNote.Stocktake.selectData.stocktake.id);
}

/** ---------------------------------------------------------------------------
 *  データ取得処理
 *  -------------------------------------------------------------------------*/
/**
 * 棚卸の情報を取得する
 *
 * @param {string} stocktakeId 棚卸ID
 */
WNote.Stocktake.Summary.getSummary = function(stocktakeId) {
    var data = WNote.ajaxValidateSend(
        '/api/stocktake/api-stocktakes/summary',
        'POST',
        {
            'stocktake_id' : stocktakeId
        }
    );

    if (!data || !data.stocktake) {
        WNote.showErrorMessage('棚卸情報の取得に失敗しました。再度タブをクリックしてください。');
    }

    return data.stocktake;
}

/**
 * 棚卸の情報を表示する
 *
 * @param {string} stocktakeId 棚卸ID
 */
WNote.Stocktake.Summary.showSummary = function(stocktakeId) {
    var stocktake = WNote.Stocktake.Summary.getSummary(stocktakeId);

    if (stocktake) {
        WNote.Form.clearTexts(WNOTE_STOCKTAKE_SUMMARY.FORM_KEY);
        WNote.Form.setTextsWithPrefix(stocktake, WNOTE_STOCKTAKE_SUMMARY.PREFIX);
    }
}
