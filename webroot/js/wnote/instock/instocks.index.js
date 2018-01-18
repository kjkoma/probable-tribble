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
/** ---------------------------------------------------------------------------
 *  定数
 *  -------------------------------------------------------------------------*/
const MYPAGE = {
    FORM_KEY: 'form-instock'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中の入庫データ */
MyPage.selectInstock = {
    instock: {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    /** select2 登録 */
    WNote.Select2.sUser('#instock_suser_id', null, false, '');

    // ユーザー初期設定
    $('#instock_suser_id').append(
        new Option(
            $('#current_suser_id').val(),
            $('#current_suser_name').val(),
            true, true)
    ).trigger('change');

    /** 各種イベント登録 **/
    WNote.registerEvent('click', 'select-new'  , MyPage.selectNewInstockHandler);
    WNote.registerEvent('click', 'select-asset', MyPage.selectAssetInstockHandler);

});

/** ---------------------------------------------------------------------------
 *  イベント処理（新規入庫制御（instocks.new.js）側で実装）
 *  -------------------------------------------------------------------------*/
/**
 * 新規入庫選択
 */
MyPage.selectNew = function() {}

/**
 * 新規入庫選択解除
 */
MyPage.unselectNew = function() {}

/**
 * 単品入庫選択
 */
MyPage.selectAsset = function() {}

/**
 * 単品入庫選択解除
 */
MyPage.unselectAsset = function() {}

/** ---------------------------------------------------------------------------
 *  イベント処理（入庫タイプ選択）
 *  -------------------------------------------------------------------------*/
/**
 * 新規入庫選択
 */
MyPage.selectNewInstockHandler = function() {
    MyPage.selectNew();
    MyPage.unselectAsset();
}

/**
 * 単品入庫選択
 */
MyPage.selectAssetInstockHandler = function() {
    MyPage.selectAsset();
    MyPage.unselectNew();
}

/** ---------------------------------------------------------------------------
 *  保存データ作成
 *  -------------------------------------------------------------------------*/
/**
 * 入庫データ保存時の検証を行う
 *
 * @result {object} 保存データ
 */
MyPage.createSaveData = function() {
    // 送信データ作成
    return WNote.Form.createAjaxData(MYPAGE.FORM_KEY);
}

/** ---------------------------------------------------------------------------
 *  検証処理
 *  -------------------------------------------------------------------------*/
/**
 * 検証状態をクリアする
 *
 */
MyPage.clearValidation = function() {
    WNote.Form.validateClear();
}

/**
 * 入庫データ保存時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.saveValidator = function(data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // plan_date
    options.rules['instock.plan_date']    = { required: true, date: true };
    options.messages['instock.plan_date'] = { required: '入庫日を入力してください。', date: '有効な入庫日をyyyy/mm/ddの形式で入力してください。' };

    // instock_suser_id
    options.rules['instock.instock_suser_id']    = { required: true };
    options.messages['instock.instock_suser_id'] = { required: '入庫担当者を選択してください。' };

    // delivery_company_id
    options.rules['instock.delivery_company_id']    = { required: true };
    options.messages['instock.delivery_company_id'] = { required: '配送業者を選択してください。' };

    // vouchar_no
    options.rules['instock.voucher_no']    = { maxlength: 40 };
    options.messages['instock.voucher_no'] = { maxlength: '伝票番号は最大40文字で入力してください。' };

    // remarks
    options.rules['instock.remarks']    = { maxlength: 2048 };
    options.messages['instock.remarks'] = { maxlength: '備考は最大2048文字で入力してください。' };

    // 入力内容の検証
    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        return WNote.Form.validateResultSet('基本情報の入力内容に不備があります。各入力内容をご確認ください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}


