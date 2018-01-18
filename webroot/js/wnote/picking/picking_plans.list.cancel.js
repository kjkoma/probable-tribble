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
 * 注意）このスクリプトを読み込む前に「picking_plans.list.js」を読み込むこと
 */
/** ---------------------------------------------------------------------------
 *  定数
 *  -------------------------------------------------------------------------*/
const MYPAGE_CANCEL = {
    FORM_KEY: "form-cancel"
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
MyPage.Cancel = {};

/** フォーム操作用 */
MyPage.Cancel.form;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // フォーム操作用インスタンスの作成
    MyPage.Cancel.form = new WNote.Lib.Form({});

    /** Widget表示イベント追加 */
    MyPage.showCancelWidgetRegister('CancelWidget', MyPage.Cancel.showWidget);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'cancel-fix'    , MyPage.Cancel.cancelFixConfirm);
    WNote.registerEvent('click', 'cancel-restore', MyPage.Cancel.restore);
});

/** ---------------------------------------------------------------------------
 *  イベント処理（取消確認Widget表示）
 *  -------------------------------------------------------------------------*/
/**
 * 取消確認Widget表示処理
 * 
 */
MyPage.Cancel.showWidget = function() {
    MyPage.Cancel.form.validateClear();
    $('#cancel_cancel_reason').val(MyPage.selectData.selected.cancel_reason);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 取消確認ボタンクリックイベントのハンドラの実装
 */
MyPage.Cancel.cancelFixConfirm = function() {
    WNote.showConfirmMessage('取消確認を行うと出庫予定は削除されますが、本当に取消の確認を行ってよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.Cancel.cancelFix;
}
/**
 * 取消確認ボタンクリック時の確認後の処理
 */
MyPage.Cancel.cancelFix = function() {
    MyPage.Cancel.save('cancel-fix');
}

/**
 * 取消解除ボタンクリックイベントのハンドラの実装
 */
MyPage.Cancel.restore = function() {
    MyPage.Cancel.save('cancel-restore');
}

/**
 * 取消確認／取消処理（※取消は取消状態を解除（未出庫状態に戻す）処理）
 */
MyPage.Cancel.save = function(saveType) {
    var saveUrl  = '/api/picking/api-picking-plans/' + saveType;

    // 送信データ作成
    var data = WNote.Form.createAjaxData(MYPAGE_CANCEL.FORM_KEY);
    data.cancel.id = MyPage.selectData.selected.id;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.Cancel.saveValidate(data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '取消確認／取消解除に失敗しました。再度実行してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic(saveUrl, 'POST',
        data,
        true,
        MyPage.Cancel.saveEditSuccess
    );
}

/**
 * 保存ボタンクリック成功時のハンドラの実装（編集時）
 *
 * @param {object} data レスポンスデータ
 */
MyPage.Cancel.saveEditSuccess = function(data) {
WNote.log(data); // debug
    MyPage.Cancel.form.validateClear();
    WNote.ajaxSuccessHandler(data, '正常に取消確認／取消解除が完了しました。一覧を再表示します。');
    MyPage.hideCancelWidget();
    MyPage.reShowPlans();
}

/**
 * データ保存時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.Cancel.saveValidate = function(data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // cancel_reason
    options.rules['cancel.cancel_reason']    = { required: true, maxlength: 2048 };
    options.messages['cancel.cancel_reason'] = { required: '取消理由を入力してください。', maxlength: '取消理由は最大2048文字で入力してください。' };

    MyPage.Cancel.form.validator = $('#' + MYPAGE_CANCEL.FORM_KEY).validate(options);
    MyPage.Cancel.form.validator.form();
    if (!MyPage.Cancel.form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    return WNote.Form.validateResultSet(message);
}
