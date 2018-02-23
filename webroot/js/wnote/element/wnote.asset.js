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
    WIDGET            : '#wid-id-elem-asset',
    TAB_KEY  : {
        ASSET   : 'elem-asset-asset-contents',
        ATTR    : 'elem-asset-attr-contents',
        USER    : 'elem-asset-user-contents',
        STOCK   : 'elem-asset-stock-contents',
        REPAIR  : 'elem-asset-repair-contents',
        RENTAL  : 'elem-asset-rental-contents'
    },
    ACTION : {
        SAVE  : 'elemAssetAdd-save',
        RENTAL: 'elemAssetRental-rental'
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Asset = {};

/** 選択データ */
WNote.Asset.selectData = {
    id    : null,
    asset : {}     // 資産取得時に設定される
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', WNOTE_ASSET.TAB_KEY.ASSET , WNote.Asset.selectAsset);
    WNote.registerEvent('click', WNOTE_ASSET.TAB_KEY.ATTR  , WNote.Asset.selectAttr);
    WNote.registerEvent('click', WNOTE_ASSET.TAB_KEY.USER  , WNote.Asset.selectUser);
    WNote.registerEvent('click', WNOTE_ASSET.TAB_KEY.STOCK , WNote.Asset.selectStock);
    WNote.registerEvent('click', WNOTE_ASSET.TAB_KEY.REPAIR, WNote.Asset.selectRepair);
    WNote.registerEvent('click', WNOTE_ASSET.TAB_KEY.RENTAL, WNote.Asset.selectRental);

    WNote.registerEvent('click', WNOTE_ASSET.ACTION.SAVE  , WNote.Asset.save);
    WNote.registerEvent('click', WNOTE_ASSET.ACTION.RENTAL, WNote.Asset.rental);

});

/** ---------------------------------------------------------------------------
 *  表示制御
 *  -------------------------------------------------------------------------*/
/**
 * 資産Widgetを表示する
 *
 * @param {string} assetId 資産ID
 */
WNote.Asset.showWidget = function(assetId) {
    WNote.Asset.selectData.id = assetId;
    WNote.Asset.changeAsset(assetId);
    $(WNOTE_ASSET.WIDGET).removeClass('hidden');
}

/**
 * 資産Widgetを非表示にする
 *
 */
WNote.Asset.hideWidget = function() {
    $(WNOTE_ASSET.WIDGET).addClass('hidden');
}

/**
 * 表示資産を変更する
 *
 * @param {string} assetId 資産ID
 */
WNote.Asset.changeAsset = function(assetId) {
    WNote.Asset.selectData.id = assetId;
    $.each(WNote.Asset.changeAssetEvents, function(key, fn) { fn(); });
}

/** 表示資産変更時イベント（利用側で関数を登録） */
WNote.Asset.changeAssetEvents = {};
WNote.Asset.changeAssetRegister = function(handleName, handler) {
    WNote.Asset.changeAssetEvents[handleName] = handler;
}

/**
 * 変更資産取得後のイベントを発行する(wnote.asset.asset.jsで資産取得時に呼び出す)
 *
 * @param {string} beforeId 変更前資産ID
 */
WNote.Asset.afterChangeAsset = function(beforeId) {
    $.each(WNote.Asset.afterChangeAssetEvents, function(key, fn) { fn(); });
}

/** 変更資産取得後イベント（利用側で関数を登録） */
WNote.Asset.afterChangeAssetEvents = {};
WNote.Asset.afterChangeAssetRegister = function(handleName, handler) {
    WNote.Asset.afterChangeAssetEvents[handleName] = handler;
}

/**
 * 資産Widgetを追加モードで表示する
 *
 */
WNote.Asset.showAddWidget = function() {
    $(WNOTE_ASSET.WIDGET).removeClass('hidden');
    WNote.Asset.showAddAsset();
    WNote.Asset.showAddAttr();
}
/** 追加モード表示時イベント（利用側で関数を登録） */
WNote.Asset.showAddAsset = function() {}
WNote.Asset.showAddAttr = function() {}

/** ---------------------------------------------------------------------------
 *  イベント処理（タブクリック）
 *  -------------------------------------------------------------------------*/
/**
 * 資産タブクリック
 *
 */
WNote.Asset.selectAsset = function() {
    WNote.Asset.selectAssetHandler();
}
/** 資産タブクリック時イベントハンドラー(利用側で実装) */
WNote.Asset.selectAssetHandler = function() {}

/**
 * 資産属性タブクリック
 *
 */
WNote.Asset.selectAttr = function() {
    WNote.Asset.selectAttrHandler();
}
/** 資産属性タブクリック時イベントハンドラー(利用側で実装) */
WNote.Asset.selectAttrHandler = function() {}

/**
 * 利用者履歴タブクリック
 *
 */
WNote.Asset.selectUser = function() {
    WNote.Asset.selectUserHandler();
}
/** 利用者履歴タブクリック時イベントハンドラー(利用側で実装) */
WNote.Asset.selectUserHandler = function() {}

/**
 * 在庫履歴タブクリック
 *
 */
WNote.Asset.selectStock = function() {
    WNote.Asset.selectStockHandler();
}
/** 在庫履歴タブクリック時イベントハンドラー(利用側で実装) */
WNote.Asset.selectStockHandler = function() {}

/**
 * 修理履歴タブクリック
 *
 */
WNote.Asset.selectRepair = function() {
    WNote.Asset.selectRepairHandler();
}
/** 修理履歴タブクリック時イベントハンドラー(利用側で実装) */
WNote.Asset.selectRepairHandler = function() {}

/**
 * 貸出／返却履歴タブクリック
 *
 */
WNote.Asset.selectRental = function() {
    WNote.Asset.selectRentalHandler();
}
/** 貸出／返却履歴タブクリック時イベントハンドラー(利用側で実装) */
WNote.Asset.selectRentalHandler = function() {}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存 - 資産追加）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリック時処理
 */
WNote.Asset.save = function() {
    // 検証
    var asset = WNote.Asset.addAssetValidate();
    var attr  = WNote.Asset.addAttrValidate();

    if (!asset || !attr) {
        WNote.ajaxValidateWarning({}, WNote.Form.validateResultSet('入力内容に不備があります。各入力内容をご確認ください。'));
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '入力された内容の保存に失敗しました。再度保存してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/asset/api-assets/add_with_attr', 'POST',
        {
            'asset' : asset,
            'attr'  : attr
        },
        true,
        WNote.Asset.saveSuccess
    );
}

/**
 * 保存成功時処理
 */
WNote.Asset.saveSuccess = function() {
    WNote.Asset.addAssetSuccess();
    WNote.Asset.addAttrSuccess();
    WNote.ajaxSuccessHandler({}, '入力された内容を保存しました。');
}

/** 保存関連処理イベント（利用側で実装） */
WNote.Asset.addAssetValidate = function() {}
WNote.Asset.addAttrValidate  = function() {}
WNote.Asset.addAssetSuccess  = function() {}
WNote.Asset.addAttrSuccess   = function() {}

/** ---------------------------------------------------------------------------
 *  イベント処理（貸出 - 貸出予定追加）
 *  -------------------------------------------------------------------------*/
/**
 * 貸出予定追加ボタンクリック時処理
 */
WNote.Asset.rental = function() {
    if (!WNote.Asset.rentalValidate()) {
        WNote.ajaxValidateWarning({}, WNote.Form.validateResultSet('指定された資産が他社により変更された可能性があります。再度、在庫を確認してください。'));
        return;
    }

    // データ更新
    WNote.ajaxFailureMessage = '該当資産の貸出予定追加に失敗しました。再度追加してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/rental/api-rentals/add', 'POST',
        {
            'asset_id' : WNote.Asset.selectData.id
        },
        true,
        WNote.Asset.rentalSuccess
    );
}

/**
 * 貸出予定追加成功時処理
 */
WNote.Asset.rentalSuccess = function() {
    WNote.ajaxSuccessHandler({}, '該当資産を貸出予定に追加しました。');
}

/**
 * 貸出予定追加時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {boolean} true: 追加可能 / false: 追加不可
 */
WNote.Asset.rentalValidate = function(data) {
    result = WNote.ajaxValidateSend(
        '/api/stock/api-stocks/validate_asset_id',
        'POST',
        {
            'asset_id': WNote.Asset.selectData.id
        }
    );

    return (result && result.validate);
}
