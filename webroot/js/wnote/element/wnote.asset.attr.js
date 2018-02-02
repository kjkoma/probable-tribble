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
const WNOTE_ASSET_ATTR = {
    FORM_KEY : {
        INPUT: 'form-elem-asset-attr',
        VIEW : 'form-elem-assetview-attr'
    },
    PREFIX   : {
        INPUT: 'elemAssetAttr_',
        VIEW : 'elemAssetviewAttr_'
    },
    AREA : {
        INPUT: '#elem-asset-attr-edit-id',
        VIEW : '#elem-asset-attr-view-id'
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Asset = WNote.Asset || {};
WNote.Asset.Attr = {};

/** 選択データ */
WNote.Asset.Attr.selectData = {
};

/** フォーム操作用 */
WNote.Asset.Attr.form;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // フォーム操作用インスタンスの作成
    WNote.Asset.Attr.form = new WNote.Lib.Form({
        'edit': 'elemAssetAttr-edit-actions',
        'view': 'elemAssetAttr-view-actions'
    });

    /* 表示資産変更イベント登録 */
    WNote.Asset.afterChangeAssetRegister('attr', WNote.Asset.Attr.show);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'elemAssetAttr-edit'  , WNote.Asset.Attr.edit);
    WNote.registerEvent('click', 'elemAssetAttr-cancel', WNote.Asset.Attr.cancel);
    WNote.registerEvent('click', 'elemAssetAttr-save'  , WNote.Asset.Attr.save);

});

/** ---------------------------------------------------------------------------
 *  タブ選択時処理（wnote.asset.js宣言の実装）
 *  -------------------------------------------------------------------------*/
/**
 * タブ選択時処理
 */
WNote.Asset.selectAttrHandler = function() {
}

/** ---------------------------------------------------------------------------
 *  資産属性追加関連処理（wnote.asset.js宣言の実装）
 *  -------------------------------------------------------------------------*/
/**
 * 資産属性追加時の表示処理
 */
WNote.Asset.showAddAttr = function() {
    WNote.Asset.Attr.form.editMode(WNOTE_ASSET_ATTR.FORM_KEY.INPUT);
    WNote.Asset.Attr.showInput();
    WNote.Asset.Attr.hideView();
}

/**
 * 資産属性追加の保存時検証処理
 */
WNote.Asset.addAttrValidate = function() {
    // 検証
    
    if (!WNote.Asset.Attr.saveValidate(data).result) {
        return false;
    }

    // 送信データ作成
    var data = WNote.Form.createAjaxData(WNOTE_ASSET_ATTR.FORM_KEY.INPUT);

    return data['elem_asset_attr'];
}

/**
 * 資産属性追加の保存成功時処理
 */
WNote.Asset.addAttrSuccess = function() {
    WNote.Asset.Attr.form.validateClear();
    WNote.Form.clearFormValues(WNOTE_ASSET_ATTR.FORM_KEY.INPUT);
}

/** ---------------------------------------------------------------------------
 *  資産属性表示処理
 *  -------------------------------------------------------------------------*/
/**
 * 資産属性を表示する
 */
WNote.Asset.Attr.show = function() {
    WNote.Form.clearFormValues(WNOTE_ASSET_ATTR.FORM_KEY.INPUT);
    WNote.Form.setFormValuesWithPrefix(WNote.Asset.selectData.asset.asset_attribute, WNOTE_ASSET_ATTR.PREFIX.INPUT);
    WNote.Form.clearTexts(WNOTE_ASSET.FORM_KEY.VIEW);
    WNote.Form.setTextsWithPrefix(WNote.Asset.selectData.asset.asset_attribute, WNOTE_ASSET_ATTR.PREFIX.VIEW);
    WNote.Asset.Attr.form.viewMode(WNOTE_ASSET_ATTR.FORM_KEY.INPUT);
    WNote.Asset.Attr.hideInput();
    WNote.Asset.Attr.showView();
}

/**
 * 入力エリアを表示する
 */
WNote.Asset.Attr.showInput = function() {
    $(WNOTE_ASSET_ATTR.AREA.INPUT).removeClass('hidden');
}

/**
 * 入力エリアを非表示にする
 */
WNote.Asset.Attr.hideInput = function() {
    $(WNOTE_ASSET_ATTR.AREA.INPUT).addClass('hidden');
}

/**
 * 表示エリアを表示する
 */
WNote.Asset.Attr.showView = function() {
    $(WNOTE_ASSET_ATTR.AREA.VIEW).removeClass('hidden');
}

/**
 * 表示エリアを非表示にする
 */
WNote.Asset.Attr.hideView = function() {
    $(WNOTE_ASSET_ATTR.AREA.VIEW).addClass('hidden');
}

/** ---------------------------------------------------------------------------
 *  イベント処理（編集画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 編集ボタンクリックイベントのハンドラの実装
 */
WNote.Asset.Attr.edit = function() {
    WNote.Asset.Attr.form.editMode(WNOTE_ASSET_ATTR.FORM_KEY.INPUT);
    WNote.Asset.Attr.showInput();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（キャンセル）
 *  -------------------------------------------------------------------------*/
/**
 * キャンセルボタンクリックイベントのハンドラの実装
 */
WNote.Asset.Attr.cancel = function() {
    WNote.Asset.Attr.form.validateClear();

    WNote.Form.clearFormValues(WNOTE_ASSET_ATTR.FORM_KEY.INPUT);
    WNote.Form.setFormValuesWithPrefix(WNote.Asset.selectData.asset.asset_attribute, WNOTE_ASSET_ATTR.PREFIX.INPUT);
    WNote.Asset.Attr.form.viewMode(WNOTE_ASSET_ATTR.FORM_KEY.INPUT);
    WNote.Asset.Attr.hideInput();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリックイベントのハンドラの実装
 */
WNote.Asset.Attr.save = function() {
    var saveUrl  = '/api/asset/api-asset-attributes/edit';

    // 送信データ作成
    var data = WNote.Form.createAjaxData(WNOTE_ASSET_ATTR.FORM_KEY.INPUT);
    data.attr    = data['elem_asset_attr'];
    data.attr.id = WNote.Asset.selectData.asset.asset_attribute.id;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, WNote.Asset.Attr.saveValidate(data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '入力された内容の保存に失敗しました。再度保存してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic(saveUrl, 'POST',
        data,
        true,
        WNote.Asset.Attr.saveEditSuccess
    );
}

/**
 * 保存ボタンクリック成功時のハンドラの実装（編集時）
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.Attr.saveEditSuccess = function(data) {
WNote.log(data); // debug
    WNote.Asset.Attr.form.validateClear();
    WNote.ajaxSuccessHandler(data, '入力された内容を保存しました。');
    WNote.Asset.Attr.show();
}

/**
 * データ保存時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
WNote.Asset.Attr.saveValidate = function(data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();
    options.ignore = '';

    // gw
    options.rules['elem_asset_attr.gw']    = { maxlength: 15 };
    options.messages['elem_asset_attr.gw'] = { maxlength: 'GWアドレスは15文字以内で入力してください。'};

    // ip
    options.rules['elem_asset_attr.ip']    = { maxlength: 15 };
    options.messages['elem_asset_attr.ip'] = { maxlength: 'IPアドレスは15文字以内で入力してください。'};

    // ip_v6
    options.rules['elem_asset_attr.ip_v6']    = { maxlength: 39 };
    options.messages['elem_asset_attr.ip_v6'] = { maxlength: 'IPアドレス(v6)は39文字以内で入力してください。'};

    // ip_wifi
    options.rules['elem_asset_attr.ip_wifi']    = { maxlength: 15 };
    options.messages['elem_asset_attr.ip_wifi'] = { maxlength: 'IPアドレス(無線)は15文字以内で入力してください。'};

    // mac
    options.rules['elem_asset_attr.mac']    = { maxlength: 18 };
    options.messages['elem_asset_attr.mac'] = { maxlength: 'MACアドレスは18文字以内で入力してください。'};

    // mac_wifi
    options.rules['elem_asset_attr.mac_wifi']    = { maxlength: 18 };
    options.messages['elem_asset_attr.mac_wifi'] = { maxlength: 'MACアドレス（無線）は18文字以内で入力してください。'};

    // subnet
    options.rules['elem_asset_attr.subnet']    = { maxlength: 15 };
    options.messages['elem_asset_attr.subnet'] = { maxlength: 'サブネットは15文字以内で入力してください。'};

    // dns
    options.rules['elem_asset_attr.dns']    = { maxlength: 15 };
    options.messages['elem_asset_attr.dns'] = { maxlength: 'DNSは15文字以内で入力してください。'};

    // dhcp
    options.rules['elem_asset_attr.dhcp']    = { maxlength: 15 };
    options.messages['elem_asset_attr.dhcp'] = { maxlength: 'DHCPは15文字以内で入力してください。'};

    // os
    options.rules['elem_asset_attr.os']    = { maxlength: 80 };
    options.messages['elem_asset_attr.os'] = { maxlength: 'OSは80文字以内で入力してください。'};

    // os_version
    options.rules['elem_asset_attr.os_version']    = { maxlength: 80 };
    options.messages['elem_asset_attr.os_version'] = { maxlength: 'OSバージョンは80文字以内で入力してください。'};

    // office
    options.rules['elem_asset_attr.office']    = { maxlength: 80 };
    options.messages['elem_asset_attr.office'] = { maxlength: 'Officeは80文字以内で入力してください。'};

    // office_remarks
    options.rules['elem_asset_attr.office_remarks']    = { maxlength: 256 };
    options.messages['elem_asset_attr.office_remarks'] = { maxlength: 'Office（補足）は256文字以内で入力してください。'};

    // software
    options.rules['elem_asset_attr.software']    = { maxlength: 2048 };
    options.messages['elem_asset_attr.software'] = { maxlength: 'ソフトウェアは2048文字以内で入力してください。'};

    // imei_no
    options.rules['elem_asset_attr.imei_no']    = { maxlength: 30 };
    options.messages['elem_asset_attr.imei_no'] = { maxlength: 'IMEI番号は30文字以内で入力してください。'};

    // certificate_no
    options.rules['elem_asset_attr.certificate_no']    = { maxlength: 30 };
    options.messages['elem_asset_attr.certificate_no'] = { maxlength: '証明書番号は30文字以内で入力してください。'};

    // apply_no
    options.rules['elem_asset_attr.apply_no']    = { maxlength: 60 };
    options.messages['elem_asset_attr.apply_no'] = { maxlength: '申請番号（購入申請など）は60文字以内で入力してください。'};

    // place
    options.rules['elem_asset_attr.place']    = { maxlength: 80 };
    options.messages['elem_asset_attr.place'] = { maxlength: '保管場所は80文字以内で入力してください。'};

    // purchase_date
    options.rules['elem_asset_attr.purchase_date']    = { date: true, dateFormat: true };
    options.messages['elem_asset_attr.purchase_date'] = { date: '有効な購入日をyyyy/mm/ddの形式で入力してください。', dateFormat: '購入日はyyyy/mm/ddの形式で入力してください。' };

    // support_term_year
    options.rules['elem_asset_attr.support_term_year']    = { maxlength: 2, min: 0, max: 99 };
    options.messages['elem_asset_attr.support_term_year'] = { maxlength: 'サポート期間（年）は0以上99以内で入力してください。', min: 'サポート期間（年）は0以上99以内で入力してください。', max: 'サポート期間（年）は0以上99以内で入力してください。' };

    // at_mouse
    options.rules['elem_asset_attr.at_mouse']    = { maxlength: 80 };
    options.messages['elem_asset_attr.at_mouse'] = { maxlength: '付属マウスは80文字以内で入力してください。'};

    // at_keyboard
    options.rules['elem_asset_attr.at_keyboard']    = { maxlength: 80 };
    options.messages['elem_asset_attr.at_keyboard'] = { maxlength: '付属キーボードは80文字以内で入力してください。'};

    // at_ac
    options.rules['elem_asset_attr.at_ac']    = { maxlength: 80 };
    options.messages['elem_asset_attr.at_ac'] = { maxlength: '付属ACは80文字以内で入力してください。'};

    // at_manual
    options.rules['elem_asset_attr.at_manual']    = { maxlength: 80 };
    options.messages['elem_asset_attr.at_manual'] = { maxlength: '付属マニュアル類は80文字以内で入力してください。'};

    // at_other
    options.rules['elem_asset_attr.at_other']    = { maxlength: 2048 };
    options.messages['elem_asset_attr.at_other'] = { maxlength: '付属その他は2048文字以内で入力してください。'};

    // local_user
    options.rules['elem_asset_attr.local_user']    = { maxlength: 30 };
    options.messages['elem_asset_attr.local_user'] = { maxlength: '管理ユーザー（ローカルPC）は30文字以内で入力してください。'};

    // local_password
    options.rules['elem_asset_attr.local_password']    = { maxlength: 30 };
    options.messages['elem_asset_attr.local_password'] = { maxlength: '管理パスワード（ローカル）は30文字以内で入力してください。'};

    // uefi_password
    options.rules['elem_asset_attr.uefi_password']    = { maxlength: 30 };
    options.messages['elem_asset_attr.uefi_password'] = { maxlength: 'UEFIパスワード(supervisor) は30文字以内で入力してください。'};

    // uefi_user_password
    options.rules['elem_asset_attr.uefi_user_password']    = { maxlength: 30 };
    options.messages['elem_asset_attr.uefi_user_password'] = { maxlength: 'UEFIパスワード(user)は30文字以内で入力してください。'};

    // hdd_password
    options.rules['elem_asset_attr.hdd_password']    = { maxlength: 30 };
    options.messages['elem_asset_attr.hdd_password'] = { maxlength: 'HDDパスワード(supervisor)は30文字以内で入力してください。'};

    // hdd_user_password
    options.rules['elem_asset_attr.hdd_user_password']    = { maxlength: 30 };
    options.messages['elem_asset_attr.hdd_user_password'] = { maxlength: 'HDDパスワード(user)は30文字以内で入力してください。'};

   WNote.Asset.Attr.form.validator = $('#' + WNOTE_ASSET_ATTR.FORM_KEY.INPUT).validate(options);
   WNote.Asset.Attr.form.validator.form();
    if (!WNote.Asset.Attr.form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    return WNote.Form.validateResultSet(message);
}

