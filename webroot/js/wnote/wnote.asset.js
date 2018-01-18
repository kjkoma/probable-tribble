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
const WNOTE_ASSET = {
    FORM_KEY          : "form-elem-asset",
    FORM_KEY_VIEW     : "form-elem-assetview",
    FORM_KEY_ATTR     : "form-elem-asset-attr",
    FORM_KEY_VIEW_ATTR: "form-elem-assetview-attr",
    PREFIX            : 'elemAsset_',
    PREFIX_VIEW       : 'elemAssetview_',
    PREFIX_ATTR       : 'elemAssetattr_',
    PREFIX_VIEW_ATTR  : 'elemAssetviewattr_',
    WIDGET            : '#wid-id-elem-asset'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Asset = {};

/** 選択データ */
WNote.Asset.selectData = {
    asset : {}
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
 * @param {boolean} showView true:ビュー表示／false: 入力表示
 * @param {boolean} showAttr true:属性表示／false: 属性非表示
 * @param {object} successHandler 成功時に呼び出すハンドラー（nullの場合デフォルト使用）
 */
WNote.Asset.getAsset = function(assetId, showView, showAttr, successHandler) {
    var handler = successHandler;
    if (!handler) {
        if (showView && !showAttr) {
            handler = WNote.Asset.getAssetViewSuccess;
        } else if (showView && showAttr) {
            handler = WNote.Asset.getAssetWithAttrViewSuccess;
        } else if (!showView && showAttr) {
            handler = WNote.Asset.getAssetWithAttrSuccess;
        } else {
            handler = WNote.Asset.getAssetSuccess;
        }
    }

    WNote.ajaxFailureMessage = '資産データの読込に失敗しました。';
    WNote.ajaxSendBasic('/api/asset/api-assets/asset', 'POST',
        { 'asset_id': assetId },
        true,
        handler
    );
}

/** ---------------------------------------------------------------------------
 *  表示制御
 *  -------------------------------------------------------------------------*/
/**
 * 資産詳細Widgetを表示する
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.showWidget = function() {
    $(WNOTE_ASSET.WIDGET).removeClass('hidden');
}

/**
 * 資産詳細Widgetを非表示にする
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.hideWidget = function() {
    $(WNOTE_ASSET.WIDGET).addClass('hidden');
}

/** ---------------------------------------------------------------------------
 *  表示処理
 *  -------------------------------------------------------------------------*/
/**
 * 資産取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.getAssetSuccess = function(data) {
    WNote.Asset.selectData.asset = data.data.param.asset;
    WNote.hideLoading();
    WNote.Form.clearFormValues(WNOTE_ASSET.FORM_KEY);
    WNote.Form.setFormValuesWithPrefix(WNote.Asset.selectData.asset, WNOTE_ASSET.PREFIX);
    WNote.Form.clearTexts(WNOTE_ASSET.FORM_KEY_VIEW);
    WNote.Form.setTextsWithPrefix(WNote.Asset.selectData.asset, WNOTE_ASSET.PREFIX_VIEW);
}
/**
 * 資産取得成功時のハンドラの実装（view用）
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.getAssetViewSuccess = function(data) {
    WNote.Asset.selectData.asset = data.data.param.asset;
    WNote.hideLoading();
    WNote.Form.clearTexts(WNOTE_ASSET.FORM_KEY_VIEW);
    WNote.Form.setTextsWithPrefix(WNote.Asset.selectData.asset, WNOTE_ASSET.PREFIX_VIEW);
}
/**
 * 資産取得成功時のハンドラの実装（属性含む）
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.getAssetWithAttrSuccess = function(data) {
    WNote.Asset.selectData.asset = data.data.param.asset;
    WNote.hideLoading();
    WNote.Form.clearFormValues(WNOTE_ASSET.FORM_KEY_ATTR);
    WNote.Form.setFormValuesWithPrefix(WNote.Asset.selectData.asset, WNOTE_ASSET.PREFIX_ATTR);
    WNote.Form.clearTexts(WNOTE_ASSET.FORM_KEY_VIEW_ATTR);
    WNote.Form.setTextsWithPrefix(WNote.Asset.selectData.asset, WNOTE_ASSET.PREFIX_VIEW_ATTR);
    WNote.Asset.getAssetSuccess(data);
}
/**
 * 資産取得成功時のハンドラの実装（属性含むview用）
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.getAssetWithAttrViewSuccess = function(data) {
    WNote.Asset.selectData.asset = data.data.param.asset;
    WNote.hideLoading();
    WNote.Form.clearTexts(WNOTE_ASSET.FORM_KEY_VIEW_ATTR);
    WNote.Form.setTextsWithPrefix(WNote.Asset.selectData.asset, WNOTE_ASSET.PREFIX_VIEW_ATTR);
    WNote.Asset.getAssetViewSuccess(data);
}

