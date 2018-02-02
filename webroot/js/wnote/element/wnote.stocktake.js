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
const WNOTE_STOCKTAKE = {
    WIDGET         : '#wid-id-elem-stocktake',
    TAB_KEY  : {
        SUMMARY : 'elem-summary-content',
        UNMATCH : 'elem-unmatch-content',
        NOSTOCK : 'elem-nostock-content'
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Stocktake = {};

/** 選択データ */
WNote.Stocktake.selectData = {
    stocktake : {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', WNOTE_STOCKTAKE.TAB_KEY.SUMMARY, WNote.Stocktake.selectSummary);
    WNote.registerEvent('click', WNOTE_STOCKTAKE.TAB_KEY.UNMATCH, WNote.Stocktake.selectUnmatch);
    WNote.registerEvent('click', WNOTE_STOCKTAKE.TAB_KEY.NOSTOCK, WNote.Stocktake.selectNostock);

});

/** ---------------------------------------------------------------------------
 *  表示制御
 *  -------------------------------------------------------------------------*/
/**
 * 棚卸Widgetを表示する
 *
 * @param {string} stocktakeId 棚卸ID
 */
WNote.Stocktake.showWidget = function(stocktakeId) {
    WNote.Stocktake.selectData.stocktake.id = stocktakeId;
    WNote.Stocktake.selectSummary();
    $(WNOTE_STOCKTAKE.WIDGET).removeClass('hidden');

}

/**
 * 棚卸Widgetを非表示にする
 *
 */
WNote.Stocktake.hideWidget = function() {
    WNote.Stocktake.selectData.stocktake = {};
    $(WNOTE_STOCKTAKE.WIDGET).addClass('hidden');
}

/** ---------------------------------------------------------------------------
 *  イベント処理（タブクリック）
 *  -------------------------------------------------------------------------*/
/**
 * 棚卸サマリタブクリック
 *
 */
WNote.Stocktake.selectSummary = function() {
    WNote.Stocktake.selectSummaryHandler();
}
/** 棚卸サマリタブクリック時イベントハンドラー(利用側で実装) */
WNote.Stocktake.selectSummaryHandler = function() {}

/**
 * 棚卸差分（数量差分）タブクリック
 *
 */
WNote.Stocktake.selectUnmatch = function() {
    WNote.Stocktake.selectUnmatchHandler();
}
/** 棚卸差分（数量差分）タブクリック時イベントハンドラー(利用側で実装) */
WNote.Stocktake.selectUnmatchHandler = function() {}

/**
 * 棚卸差分（在庫なし）タブクリック
 *
 */
WNote.Stocktake.selectNostock = function() {
    WNote.Stocktake.selectNostockHandler();
}
/** 棚卸差分（在庫なし）タブクリック時イベントハンドラー(利用側で実装) */
WNote.Stocktake.selectNostockHandler = function() {}


