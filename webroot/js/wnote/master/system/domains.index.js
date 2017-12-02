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
    FORM_KEY: "form-domain"
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中のドメインデータ */
MyPage.selectDomain = {
    domain: {},
    apps: {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'edit'   , MyPage.edit);
    WNote.registerEvent('click', 'add'    , MyPage.add);
    WNote.registerEvent('click', 'cancel' , MyPage.cancel);
    WNote.registerEvent('click', 'save'   , MyPage.save);
    WNote.registerEvent('click', 'delete' , MyPage.delete);

});

/** ---------------------------------------------------------------------------
 *  イベント処理（ドメイン選択）
 *  -------------------------------------------------------------------------*/
/**
 * サイドリスト（Element/Parts/side-list）用クリックイベントのハンドラの実装
 */
WNote.sideListClickHandler = function(event) {
    // 選択ドメインの取得
    var id = $(event.target).attr(WNOTE.DATA_ATTR.ID);

    // 文字の部分選択時はSPANタグ要素がevent.targetになっているので親要素よりIDを取得する
    if ($(event.target).prop("tagName") == "SPAN") {
        id = $(event.target).parent().attr(WNOTE.DATA_ATTR.ID);
    }

    // ドメインの取得
    MyPage.getDomain(id);
}

/**
 * ドメイン取得
 *
 * @param {integer} domain_id ドメインID
 */
MyPage.getDomain = function(domain_id) {
    // ドメインデータの取得
    WNote.ajaxFailureMessage = 'ドメインデータの読込に失敗しました。再度ドメインを選択してください。';
    WNote.ajaxSendBasic('/api/master/system/api-domains/domain', 'POST',
        { 'domain_id': domain_id },
        true,
        MyPage.getDomainSuccess
    );
}

/**
 * ドメイン取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.getDomainSuccess = function(data) {
    MyPage.selectDomain.domain = data.data.param.domain;
    MyPage.selectDomain.apps   = (data.data.param.domainApps) ? data.data.param.domainApps : {};
    WNote.Form.viewMode(MYPAGE.FORM_KEY);
    WNote.hideLoading();
    MyPage.setFormValues();
}

/**
 * ドメインデータをフォームに表示する
 * 
 */
MyPage.setFormValues = function() {
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.setFormValues(MyPage.selectDomain.domain);
    WNote.Form.setTableFormValues('tbl-checkbox', MyPage.selectDomain.apps, 'sapp_id', null);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（編集画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 編集ボタンクリックイベントのハンドラの実装
 */
MyPage.edit = function() {
    WNote.Form.editMode(MYPAGE.FORM_KEY);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（追加画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 追加ボタンクリックイベントのハンドラの実装
 */
MyPage.add = function() {
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.addMode(MYPAGE.FORM_KEY);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（キャンセル）
 *  -------------------------------------------------------------------------*/
/**
 * キャンセルボタンクリックイベントのハンドラの実装
 */
MyPage.cancel = function() {
    WNote.Form.validateClear();

    if (WNote.Form.formActionStatus.Before == WNOTE.FORM_STATUS.INIT) {
        WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
        WNote.Form.initMode(MYPAGE.FORM_KEY);
    } else {
        MyPage.setFormValues();
        WNote.Form.viewMode(MYPAGE.FORM_KEY);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリックイベントのハンドラの実装
 */
MyPage.save = function() {
    var saveUrl  = '/api/master/system/api-domains/';
    var saveType = 'add';
    var successHandler = MyPage.saveAddSuccess;

    if (WNote.Form.formActionStatus.Current == WNOTE.FORM_STATUS.EDIT) {
        saveType       = 'edit';
        successHandler = MyPage.saveEditSuccess;
    }

    // 送信データ作成
    var data = WNote.Form.createAjaxData(MYPAGE.FORM_KEY);

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.saveValidate(saveType, data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '入力された内容の保存に失敗しました。再度保存してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic(saveUrl + saveType, 'POST',
        data,
        true,
        successHandler
    );

}
/**
 * 保存ボタンクリック成功時のハンドラの実装（追加時）
 *
 * @param {object} data レスポンスデータ
 */
MyPage.saveAddSuccess = function(data) {
WNote.log(data); // debug
    MyPage.getDomain(data.data.param.domain.id);
    WNote.ajaxSuccessHandler(data, '入力された内容を登録しました。ページを再表示します。');
    location.reload();
}
/**
 * 保存ボタンクリック成功時のハンドラの実装（編集時）
 *
 * @param {object} data レスポンスデータ
 */
MyPage.saveEditSuccess = function(data) {
WNote.log(data); // debug
    WNote.Form.viewMode(MYPAGE.FORM_KEY);
    WNote.Form.validateClear();
    WNote.ajaxSuccessHandler(data, '入力された内容を保存しました。');
}

/**
 * ドメインデータ保存時の検証を行う
 *
 * @param {string} saveType 保存タイプ（add or edit）
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.saveValidate = function(saveType, data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // kanme 
    options.rules['domain.kname']    = { required: true, maxlength: 6, uniqueKname: true };
    options.messages['domain.kname'] = { required: '表示名を入力してください。', maxlength: '表示名は最大6文字で入力してください。' };

    // name
    options.rules['domain.name']    = { required: true, maxlength: 40 };
    options.messages['domain.name'] = { required: 'ドメイン名を入力してください。', maxlength: 'ドメイン名は最大40文字で入力してください。' };

    // remarks
    options.rules['domain.remarks']    = { maxlength: 512 };
    options.messages['domain.remarks'] = { maxlength: '備考は最大512文字で入力してください。' };

    // dsts
    options.rules['domain.dsts']    = { required: true };
    options.messages['domain.dsts'] = { required: '使用中／停止を選択してください。' };

    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    if (saveType == "edit" && (!data || data == '')) {
        message = 'ドメインが選択されていません。ドメインを選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（削除）
 *  -------------------------------------------------------------------------*/
/**
 * 削除ボタンクリックイベントのハンドラの実装
 */
MyPage.delete = function() {
    WNote.showConfirmMessage('ドメインを削除するとドメインに関連するデータがすべて削除され、復元することができません。本当に削除してもよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.deleteDomain;
}
/**
 * 削除ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.deleteSuccess = function(data) {
WNote.log(data); // debug
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.initMode(MYPAGE.FORM_KEY);
    WNote.ajaxSuccessHandler(data, '選択されたデータが正常に削除されました。ページを再表示します。');
    location.reload();
}

/**
 * ドメインデータを削除する
 *
 */
MyPage.deleteDomain = function() {
    // 送信データ作成
    var domain_id = (MyPage.selectDomain.domain['id']) ? MyPage.selectDomain.domain['id'] : undefined;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(domain_id, MyPage.deleteValidate(domain_id))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = 'ドメインデータの削除に失敗しました。再度削除してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/master/system/api-domains/delete', 'POST',
        { 'id': domain_id },
        true,
        MyPage.deleteSuccess
    );
}

/**
 * ドメインデータ削除時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.deleteValidate = function(data) {
    var message;

    if (!data || data == '') {
        message = 'ドメインが選択されていません。ドメインを選択してください。';
    }

    if ($('#page_current_id').val() == data) {
        message = '現在のドメインを削除することはできません。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  Validator追加メソッド
 *  -------------------------------------------------------------------------*/
/**
 * ドメイン識別子のユニークチェックの追加
 *
 */
$.validator.addMethod('uniqueKname', function(value, element) {
    result = WNote.ajaxValidateSend(
        '/api/master/system/api-domains/validate_kname',
        'POST',
        {
            'id': function(){
                return $('input[name="domain.id"]').val();
            },
            'kname': function(){
                return $('input[name="domain.kname"]').val();
            }
        }
    );

    return this.optional(element) || (result && result.validate);
}, '入力された表示名は既に利用されています。別の表示名を入力してください。');


