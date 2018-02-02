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
const WNOTE_STOCKTAKE_WORK = {
    FORM_KEY : "form-elem-stocktake-work",
    PREFIX   : 'elemStocktakeWork_',
    WIDGET   : '#wid-id-elem-stocktake-work',
    TAB_KEY  : {
        ASSET: 'elem-stocktake-work-asset',
        COUNT: 'elem-stocktake-work-count',
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.StocktakeWork = {};

/** 選択データ */
WNote.StocktakeWork.selectData = {
    stocktakeId : {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', WNOTE_STOCKTAKE_WORK.TAB_KEY.ASSET, WNote.StocktakeWork.selectAsset);
    WNote.registerEvent('click', WNOTE_STOCKTAKE_WORK.TAB_KEY.COUNT, WNote.StocktakeWork.selectCount);

});

/** ---------------------------------------------------------------------------
 *  表示制御
 *  -------------------------------------------------------------------------*/
/**
 * 棚卸実施Widgetを表示する
 *
 * @param {string} stocktakeId 棚卸ID
 */
WNote.StocktakeWork.showWidget = function(stocktakeId) {
    WNote.StocktakeWork.selectData.stocktakeId = stocktakeId;
    $(WNOTE_STOCKTAKE_WORK.WIDGET).removeClass('hidden');
}

/**
 * 棚卸実施Widgetを非表示にする
 *
 */
WNote.StocktakeWork.hideWidget = function() {
    $(WNOTE_STOCKTAKE_WORK.WIDGET).addClass('hidden');
}

/** ---------------------------------------------------------------------------
 *  イベント処理（タブクリック）
 *  -------------------------------------------------------------------------*/
/**
 * 資産棚卸タブクリック
 *
 */
WNote.StocktakeWork.selectAsset = function() {
    WNote.StocktakeWork.selectAssetHandler();
}
/** 資産棚卸選択時イベントハンドラー(利用側で実装) */
WNote.StocktakeWork.selectAssetHandler = function() {}

/**
 * 数量棚卸タブクリック
 *
 */
WNote.StocktakeWork.selectCount = function() {
    WNote.StocktakeWork.selectCountHandler();
}
/** 数量棚卸選択時イベントハンドラー(利用側で実装) */
WNote.StocktakeWork.selectCountHandler = function() {}

