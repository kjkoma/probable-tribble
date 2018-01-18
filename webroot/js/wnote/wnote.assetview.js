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
const WNOTE_ASEET = {
    FORM_KEY_ASSET : "form-elem-assetview",
    FORM_KEY_ATTR  : "form-elem-assetview-attr",
    PREFIX_ASSET   : 'elemAssetView_',
    PREFIX_ATTR    : 'elemAssetViewAttr_'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.AssetView = {};
WNote.AssetView.enable = {
    asset : true,
    attr  : true,
    user  : false,
    stock : false,
    repair: false,
    rental: false
}

/** 選択データ */
WNote.AssetView.selectData = {
    asset : {},
    attr  : {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {
});

/** ---------------------------------------------------------------------------
 *  取得処理
 *  -------------------------------------------------------------------------*/
/**
 * 資産情報取得
 * 
 * @param {string} assetId 資産ID
 * @param {object} successHandler 成功時に呼び出すハンドラー（nullの場合デフォルト使用）
 */
WNote.AssetView.getAsset = function(assetId, showView, showAttr, successHandler) {
    var handler = successHandler;
    if (!handler) {
        handler = WNote.AssetView.getAssetSuccess;
    }

    WNote.ajaxFailureMessage = '資産データの読込に失敗しました。';
    WNote.ajaxSendBasic('/api/asset/api-assets/asset', 'POST',
        { 'asset_id': assetId },
        true,
        handler
    );
}

/** ---------------------------------------------------------------------------
 *  表示処理
 *  -------------------------------------------------------------------------*/
/**
 * 資産取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.AssetView.getAssetSuccess = function(data) {
    WNote.AssetView.selectData.asset = data.data.param.asset;
    WNote.AssetView.selectData.attr  = data.data.param.asset.asset_attribute;
    WNote.hideLoading();

    // 各タブ表示
    WNote.AssetView.showAsset();
    WNote.AssetView.showAttr();
    WNote.AssetView.showUser();
    WNote.AssetView.showStock();
    WNote.AssetView.showRepair();
    WNote.AssetView.showRental();
}

/** ---------------------------------------------------------------------------
 *  タブ表示制御
 *  -------------------------------------------------------------------------*/
/**
 * 資産タブ表示
 *
 * @param {object} data レスポンスデータ
 */
WNote.AssetView.showAttr = function(data) {
    if (!WNote.AssetView.enable.asset) return;

    WNote.Form.clearTexts(WNOTE_ASSETVIEW.FORM_KEY_ASSET);
    WNote.Form.setTextsWithPrefix(WNote.AssetView.selectData.asset, WNOTE_ASSETVIEW.PREFIX_ASSET);
}
/**
 * 資産属性タブ表示
 *
 * @param {object} data レスポンスデータ
 */
WNote.AssetView.showAttr = function(data) {
    if (!WNote.AssetView.enable.attr) return;

    WNote.Form.clearTexts(WNOTE_ASSETVIEW.FORM_KEY_ATTR);
    WNote.Form.setTextsWithPrefix(WNote.AssetView.selectData.attr, WNOTE_ASSETVIEW.PREFIX_ATTR);
}

/**
 * 利用者履歴タブ表示
 *
 * @param {object} data レスポンスデータ
 */
WNote.AssetView.showUser = function(data) {
    if (!WNote.AssetView.enable.uesr) return;;
}

/**
 * 在庫／在庫変動履歴タブ表示
 *
 * @param {object} data レスポンスデータ
 */
WNote.AssetView.showStock = function(data) {
    if (!WNote.AssetView.enable.stock) return;
}

/**
 * 修理履歴タブ表示
 *
 * @param {object} data レスポンスデータ
 */
WNote.AssetView.showRepair = function(data) {
    if (!WNote.AssetView.enable.repair) return;
}

/**
 * 貸出・返却履歴タブ表示
 *
 * @param {object} data レスポンスデータ
 */
WNote.AssetView.showRental = function(data) {
    if (!WNote.AssetView.enable.rental) return;
}

