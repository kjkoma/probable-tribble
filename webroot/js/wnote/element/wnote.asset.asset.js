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
const WNOTE_ASSET_ASSET = {
    FORM_KEY : {
        INPUT: 'form-elem-asset',
        VIEW : 'form-elem-assetview'
    },
    PREFIX   : {
        INPUT: 'elemAsset_',
        VIEW : 'elemAssetview_'
    },
    AREA : {
        INPUT: '#elem-asset-edit-id',
        VIEW : '#elem-asset-view-id'
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Asset = WNote.Asset || {};
WNote.Asset.Asset = {};

/** 選択データ */
WNote.Asset.Asset.selectData = {
};

/** フォーム操作用 */
WNote.Asset.Asset.form;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // フォーム操作用インスタンスの作成
    WNote.Asset.Asset.form = new WNote.Lib.Form({
        'edit': 'elemAsset-edit-actions',
        'view': 'elemAsset-view-actions'
    });

    /* 表示資産変更イベント登録 */
    WNote.Asset.changeAssetRegister('asset', WNote.Asset.Asset.show);

    /** select2 登録 */
    WNote.Select2.classification('#elemAsset_classification_id', null                          , true, '分類選択');
    WNote.Select2.product('#elemAsset_product_id'              , '#elemAsset_classification_id', true, '製品選択');
    WNote.Select2.model('#elemAsset_product_model_id'          , '#elemAsset_product_id'       , true, 'モデル選択');
    WNote.Select2.sUser('#elemAsset_abrogate_suser_id'         , null                          , true, '廃棄者');

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'elemAsset-edit'  , WNote.Asset.Asset.edit);
    WNote.registerEvent('click', 'elemAsset-cancel', WNote.Asset.Asset.cancel);
    WNote.registerEvent('click', 'elemAsset-save'  , WNote.Asset.Asset.save);

});

/** ---------------------------------------------------------------------------
 *  タブ選択時処理（wnote.asset.js宣言の実装）
 *  -------------------------------------------------------------------------*/
/**
 * タブ選択時処理
 */
WNote.Asset.selectAssetHandler = function() {
}

/** ---------------------------------------------------------------------------
 *  資産追加関連処理（wnote.asset.js宣言の実装）
 *  -------------------------------------------------------------------------*/
/**
 * 資産追加時の表示処理
 */
WNote.Asset.showAddAsset = function() {
    WNote.Asset.Asset.form.editMode(WNOTE_ASSET_ASSET.FORM_KEY.INPUT);
    WNote.Asset.Asset.showInput();
    WNote.Asset.Asset.hideView();
}

/**
 * 資産追加の保存時検証処理
 */
WNote.Asset.addAssetValidate = function() {
    // 検証
    if (!WNote.Asset.Asset.saveValidate(data, true).result) {
        return false;
    }

    // 送信データ作成
    var data = WNote.Form.createAjaxData(WNOTE_ASSET_ASSET.FORM_KEY.INPUT);

    return data['elem_asset'];
}

/**
 * 資産追加の保存成功時処理
 */
WNote.Asset.addAssetSuccess = function() {
    WNote.Asset.Asset.form.validateClear();
    WNote.Form.clearFormValues(WNOTE_ASSET_ASSET.FORM_KEY.INPUT);
}

/** ---------------------------------------------------------------------------
 *  資産表示処理
 *  -------------------------------------------------------------------------*/
/**
 * 資産を表示する
 */
WNote.Asset.Asset.show = function() {
    WNote.Asset.Asset.getAsset(WNote.Asset.selectData.id);
    WNote.Asset.Asset.hideInput();
    WNote.Asset.Asset.showView();
}

/**
 * 入力エリアを表示する
 */
WNote.Asset.Asset.showInput = function() {
    $(WNOTE_ASSET_ASSET.AREA.INPUT).removeClass('hidden');
}

/**
 * 入力エリアを非表示にする
 */
WNote.Asset.Asset.hideInput = function() {
    $(WNOTE_ASSET_ASSET.AREA.INPUT).addClass('hidden');
}

/**
 * 表示エリアを表示する
 */
WNote.Asset.Asset.showView = function() {
    $(WNOTE_ASSET_ASSET.AREA.VIEW).removeClass('hidden');
}

/**
 * 表示エリアを非表示にする
 */
WNote.Asset.Asset.hideView = function() {
    $(WNOTE_ASSET_ASSET.AREA.VIEW).addClass('hidden');
}

/** ---------------------------------------------------------------------------
 *  データ取得処理
 *  -------------------------------------------------------------------------*/
/**
 * 資産を取得する
 *
 * @param {string} assetId 資産ID
 */
WNote.Asset.Asset.getAsset = function(assetId) {
    WNote.ajaxFailureMessage = '資産データの読込に失敗しました。';
    WNote.ajaxSendBasic('/api/asset/api-assets/asset', 'POST',
        { 'asset_id': assetId },
        false,
        WNote.Asset.Asset.getAssetSuccess
    );
}

/**
 * 資産取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.Asset.getAssetSuccess = function(data) {
    WNote.Asset.selectData.asset = data.data.param.asset; // wnote.asset.jsの変数に設定
    WNote.Form.clearFormValues(WNOTE_ASSET_ASSET.FORM_KEY.INPUT);
    WNote.Form.setFormValuesWithPrefix(WNote.Asset.selectData.asset, WNOTE_ASSET_ASSET.PREFIX.INPUT);
    WNote.Form.clearTexts(WNOTE_ASSET.FORM_KEY.VIEW);
    WNote.Form.setTextsWithPrefix(WNote.Asset.selectData.asset, WNOTE_ASSET_ASSET.PREFIX.VIEW);
    WNote.Asset.Asset.form.viewMode(WNOTE_ASSET_ASSET.FORM_KEY.INPUT);
    WNote.Asset.afterChangeAsset(); // 資産取得を通知する
}

/** ---------------------------------------------------------------------------
 *  イベント処理（編集画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 編集ボタンクリックイベントのハンドラの実装
 */
WNote.Asset.Asset.edit = function() {
    WNote.Asset.Asset.form.editMode(WNOTE_ASSET_ASSET.FORM_KEY.INPUT);
    WNote.Asset.Asset.showInput();

    if (WNote.Asset.selectData.asset.asset_type != $('#elemAsset_asset_type_asset').val()) {
        $('#elemAsset_product_id').prop('disabled', true);       // 数量管理の場合、製品変更不可
        $('#elemAsset_product_model_id').prop('disabled', true); // 数量管理の場合、モデル変更不可
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（キャンセル）
 *  -------------------------------------------------------------------------*/
/**
 * キャンセルボタンクリックイベントのハンドラの実装
 */
WNote.Asset.Asset.cancel = function() {
    WNote.Asset.Asset.form.validateClear();

    WNote.Form.clearFormValues(WNOTE_ASSET_ASSET.FORM_KEY.INPUT);
    WNote.Form.setFormValuesWithPrefix(WNote.Asset.selectData.asset, WNOTE_ASSET_ASSET.PREFIX.INPUT);
    WNote.Asset.Asset.form.viewMode(WNOTE_ASSET_ASSET.FORM_KEY.INPUT);
    WNote.Asset.Asset.hideInput();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリックイベントのハンドラの実装
 */
WNote.Asset.Asset.save = function() {
    var saveUrl  = '/api/asset/api-assets/edit';

    // 送信データ作成
    var data = WNote.Form.createAjaxData(WNOTE_ASSET_ASSET.FORM_KEY.INPUT);
    data.asset    = data['elem_asset'];
    data.asset.id = WNote.Asset.selectData.id;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, WNote.Asset.Asset.saveValidate(data, false))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '入力された内容の保存に失敗しました。再度保存してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic(saveUrl, 'POST',
        data,
        true,
        WNote.Asset.Asset.saveEditSuccess
    );
}

/**
 * 保存ボタンクリック成功時のハンドラの実装（編集時）
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.Asset.saveEditSuccess = function(data) {
WNote.log(data); // debug
    WNote.Asset.Asset.form.validateClear();
    WNote.ajaxSuccessHandler(data, '入力された内容を保存しました。');
    WNote.Asset.Asset.show();
}

/**
 * データ保存時の検証を行う
 *
 * @param {object} data 送信データ
 * @param {boolean} checkDuplicate 重複チェック実施有無（true: 実施、false: 実施しない）
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
WNote.Asset.Asset.saveValidate = function(data, checkDuplicate) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();
    options.ignore = '';

    // serial_no
    options.rules['elem_asset.serial_no']    = { required: true, maxlength: 120, uniqueSerialNo: checkDuplicate };
    options.messages['elem_asset.serial_no'] = { required: 'シリアル番号を入力してください', maxlength: 'シリアル番号は120文字以内で入力してください。'};

    // asset_no
    options.rules['elem_asset.asset_no']    = { maxlength: 60 };
    options.messages['elem_asset.asset_no'] = { maxlength: '資産管理番号は60文字以内で入力してください。'};

    // kname
    options.rules['elem_asset.kname']    = { maxlength: 100 };
    options.messages['elem_asset.kname'] = { maxlength: '資産名称は100文字以内で入力してください。'};

    // product_id
    options.rules['elem_asset.product_id']    = { required: true, uniqueCountAsset: checkDuplicate };
    options.messages['elem_asset.product_id'] = { required: '製品を選択してください。'};

    // asset_sts
    options.rules['elem_asset.asset_sts']    = { required: true };
    options.messages['elem_asset.asset_sts'] = { required: '資産状況を選択してください。'};

    // asset_sub_sts
    options.rules['elem_asset.asset_sub_sts']    = { required: true };
    options.messages['elem_asset.asset_sub_sts'] = { required: '資産状況(サブ)' };

    // first_instock_date
    options.rules['elem_asset.first_instock_date']    = { date: true, dateFormat: true };
    options.messages['elem_asset.first_instock_date'] = { date: '有効な初回入庫日をyyyy/mm/ddの形式で入力してください。', dateFormat: '初回入庫日はyyyy/mm/ddの形式で入力してください。' };

    // account_date
    options.rules['elem_asset.account_date']    = { date: true, dateFormat: true };
    options.messages['elem_asset.account_date'] = { date: '有効な初回出荷日(計上日) をyyyy/mm/ddの形式で入力してください。', dateFormat: '初回出荷日(計上日) はyyyy/mm/ddの形式で入力してください。' };

    // abrogate_date
    options.rules['elem_asset.abrogate_date']    = { date: true, dateFormat: true };
    options.messages['elem_asset.abrogate_date'] = { date: '有効な廃棄日をyyyy/mm/ddの形式で入力してください。', dateFormat: '廃棄日はyyyy/mm/ddの形式で入力してください。' };

    // support_limit_date
    options.rules['elem_asset.support_limit_date']    = { date: true, dateFormat: true };
    options.messages['elem_asset.support_limit_date'] = { date: '有効な保守期限日 をyyyy/mm/ddの形式で入力してください。', dateFormat: '保守期限日 はyyyy/mm/ddの形式で入力してください。' };

    // remarks
    options.rules['elem_asset.remarks']    = { maxlength: 2048 };
    options.messages['elem_asset.remarks'] = { maxlength: '補足は2048文字以内で入力してください。'};

   WNote.Asset.Asset.form.validator = $('#' + WNOTE_ASSET_ASSET.FORM_KEY.INPUT).validate(options);
   WNote.Asset.Asset.form.validator.form();
    if (!WNote.Asset.Asset.form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  Validator追加メソッド
 *  -------------------------------------------------------------------------*/
/**
 * シリアル番号のユニークチェックの追加
 *
 */
$.validator.addMethod('uniqueSerialNo', function(value, element) {
    result = WNote.ajaxValidateSend(
        '/api/asset/api-assets/validate_duplicate_serial_no',
        'POST',
        {
            'product_id': function(){
                return $('input[name="elem_asset.product_id"]').val();
            },
            'product_model_id': function(){
                return $('input[name="elem_asset.product_model_id"]').val();
            },
            'serial_no': function(){
                return $('input[name="elem_asset.serial_no"]').val();
            }
        }
    );

    return this.optional(element) || (result && result.validate);
}, '入力されたシリアル番号は既に登録されています。シリアル番号、既存資産を確認してください。');

/**
 * 数量管理資産のユニークチェックの追加
 *
 */
$.validator.addMethod('uniqueCountAsset', function(value, element) {
    result = WNote.ajaxValidateSend(
        '/api/asset/api-assets/validate_duplicate_count_asset',
        'POST',
        {
            'product_id': function(){
                return $('input[name="elem_asset.product_id"]').val();
            },
            'product_model_id': function(){
                return $('input[name="elem_asset.product_model_id"]').val();
            }
        }
    );

    return this.optional(element) || (result && result.validate);
}, '入力された製品、モデル／型は既に登録されています。製品、モデル／型、および、既存資産を確認してください。');

