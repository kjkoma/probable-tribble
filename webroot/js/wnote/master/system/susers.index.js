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
    FORM_KEY: "form-suser"
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中のユーザーデータ */
MyPage.selectSuser = {
    suser: {},
    domains: {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {
    // データテーブル初期化
    var responsiveHelper = undefined;
    var breakpointDefinition = { tablet: 1024, phone: 480 }
    $('#side-datatable').dataTable({
        "iDisplayLength": 15,
        "order": [],
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-12'f>r<'col-xs-4 col-sm-4 dt-users-add text-right'>>"+
            "t"+
            "<'dt-toolbar-footer'<'col-xs-12 col-sm-4 hidden-xs'i><'col-xs-12 col-sm-8'p>>",
        "autoWidth" : true,
        "oLanguage": {
              "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
        },
        "preDrawCallback" : function() {
            if (!responsiveHelper) {
                responsiveHelper = new ResponsiveDatatablesHelper($('#side-datatable'), breakpointDefinition);
            }
        },
        "rowCallback" : function(nRow) {
            responsiveHelper.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
            responsiveHelper.respond();
        },
        "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
            return iStart + "-" + iEnd + " / " + iTotal;
        }
    });

    // Datatableのヘッダーにボタンを追加する例
    // $("div.dt-users-add").html('<button class="btn btn-success btn-sm">追加</button>');

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'edit'   , MyPage.edit);
    WNote.registerEvent('click', 'add'    , MyPage.add);
    WNote.registerEvent('click', 'cancel' , MyPage.cancel);
    WNote.registerEvent('click', 'save'   , MyPage.save);
    WNote.registerEvent('click', 'delete' , MyPage.delete);
});

/** ---------------------------------------------------------------------------
 *  イベント処理（ユーザー選択）
 *  -------------------------------------------------------------------------*/
/**
 * サイドリスト（Element/Parts/side-datatable）用クリックイベントのハンドラの実装
 */
WNote.sideDatatableClickHandler = function(event) {
    // 選択ユーザーの取得
    var id = $(event.target).attr(WNOTE.DATA_ATTR.ID);

    // 文字の部分選択時はSPANタグ要素がevent.targetになっているので親要素よりIDを取得する
    if ($(event.target).prop("tagName") == "SPAN") {
        id = $(event.target).parent().attr(WNOTE.DATA_ATTR.ID);
    }

    // ユーザーの取得
    MyPage.getSuser(id);
}

/**
 * ユーザー取得
 *
 * @param {integer} suser_id ユーザーID
 */
MyPage.getSuser = function(suser_id) {
    // ユーザーデータの取得
    WNote.ajaxFailureMessage = 'ユーザーデータの読込に失敗しました。再度ユーザーを選択してください。';
    WNote.ajaxSendBasic('/api/master/system/api-susers/suser', 'POST',
        { 'suser_id': suser_id },
        true,
        MyPage.getSuserSuccess
    );
}

/**
 * ユーザー取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.getSuserSuccess = function(data) {
    MyPage.selectSuser.suser = data.data.param.suser;
    MyPage.selectSuser.domains   = (data.data.param.suserDomains) ? data.data.param.suserDomains : {};
    WNote.Form.viewMode(MYPAGE.FORM_KEY);
    WNote.hideLoading();
    MyPage.setFormValues();
}

/**
 * ユーザーデータをフォームに表示する
 * 
 */
MyPage.setFormValues = function() {
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.setFormValues(MyPage.selectSuser.suser);
    WNote.Form.setTableFormValues('tbl-checkbox', MyPage.selectSuser.domains, 'domain_id', null);
    WNote.Form.setTableFormValues('tbl-srole'   , MyPage.selectSuser.domains, 'srole_id', 'domain_id');
    WNote.Form.setTableFormValues('tbl-default' , MyPage.selectSuser.domains, 'default_domain', 'domain_id');
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
    var saveUrl  = '/api/master/system/api-susers/';
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
    MyPage.getSuser(data.data.param.suser.id);
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
 * ユーザーデータ保存時の検証を行う
 *
 * @param {string} saveType 保存タイプ（add or edit）
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.saveValidate = function(saveType, data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // email 
    options.rules['suser.email']    = { required: true, maxlength: 255, email: true, uniqueEmail: true };
    options.messages['suser.email'] = { required: 'Emailアドレスを入力してください。', maxlength: 'Emailアドレスは最大255文字で入力してください。', email: 'Emailアドレスの形式が違います。' };

    // kanme 
    options.rules['suser.kname']    = { required: true, maxlength: 12, uniqueKname: true };
    options.messages['suser.kname'] = { required: '表示名を入力してください。', maxlength: '表示名は最大12文字で入力してください。', remote: '表示名はユニークな値にしてください。' };

    // sname
    options.rules['suser.sname']    = { required: true, maxlength: 16 };
    options.messages['suser.sname'] = { required: 'ユーザー名（姓）を入力してください。', maxlength: 'ユーザー名（姓）は最大16文字で入力してください。' };

    // fname
    options.rules['suser.fname']    = { required: true, maxlength: 16 };
    options.messages['suser.fname'] = { required: 'ユーザー名（名）を入力してください。', maxlength: 'ユーザー名（名）は最大16文字で入力してください。' };

    if (WNote.Form.formActionStatus.Current == WNOTE.FORM_STATUS.ADD
        || $.trim($('input[name="suser.password"]').val()) != "") {
        // password
        options.rules['suser.password']    = { required: true, minlength: 6, maxlength: 16 };
        options.messages['suser.password'] = { required: 'パスワードを入力してください。', minlength: 'パスワードは6文字以上で入力してください。', maxlength: 'パスワードは最大16文字で入力してください。' };

        // password_confirmation
        options.rules['suser.password_confirmation']    = { equalTo: 'input[name="suser.password"]' };
        options.messages['suser.password_confirmation'] = { equalTo: 'パスワードが一致していません。パスワードに入力した値と同一の値を入力してください。' };
    }

    // remarks
    options.rules['suser.remarks']    = { maxlength: 512 };
    options.messages['suser.remarks'] = { maxlength: '備考は最大512文字で入力してください。' };

    // dsts
    options.rules['suser.dsts']    = { required: true };
    options.messages['suser.dsts'] = { required: '使用中／停止を選択してください。' };

    // 入力内容の検証
    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        return WNote.Form.validateResultSet('入力内容に不備があります。各入力内容をご確認ください。');
    }

    // デフォルトドメインの選択チェック
    if (!MyPage.hasDefault()) {
        return WNote.Form.validateResultSet('デフォルトのドメインが選択されていません。ドメインを選択してください。');
    }

    // 選択していないケース（※存在してはならないが・・）の検証
    if (saveType == "edit" && (!data || data == '')) {
        return WNote.Form.validateResultSet('ユーザーが選択されていません。ユーザーを選択してください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}

/**
 * デフォルトチェックのチェック有無を検証する
 *
 * @result {booleam} true:チェックがある/false:チェックが１つもない
 */
MyPage.hasDefault = function() {
    var check = false;
    $('[name="suser_domains.default_domain"]').each(function(i, e) {
        var row      = $(e).attr(WNOTE.DATA_ATTR.ROW);
        var selected = $('[' + WNOTE.DATA_ATTR.KEY + '="tbl-checkbox"][' + WNOTE.DATA_ATTR.ID + '="' + row + '"]').prop('checked');
        if (selected && $(e).prop('checked')) {
            check = true;
            return false;
        }
    });
    return check;
}

/** ---------------------------------------------------------------------------
 *  イベント処理（削除）
 *  -------------------------------------------------------------------------*/
/**
 * 削除ボタンクリックイベントのハンドラの実装
 */
MyPage.delete = function() {
    WNote.showConfirmMessage('ユーザーを削除するとユーザーに関連するデータがすべて削除され、復元することができません。本当に削除してもよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.deleteSuser;
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
 * ユーザーデータを削除する
 *
 */
MyPage.deleteSuser = function() {
    // 送信データ作成
    var suser_id = (MyPage.selectSuser.suser['id']) ? MyPage.selectSuser.suser['id'] : undefined;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(suser_id, MyPage.deleteValidate(suser_id))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = 'ユーザーデータの削除に失敗しました。再度削除してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/master/system/api-susers/delete', 'POST',
        { 'id': suser_id },
        true,
        MyPage.deleteSuccess
    );
}

/**
 * ユーザーデータ削除時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.deleteValidate = function(data) {
    var message;

    if (!data || data == '') {
        message = 'ユーザーが選択されていません。ユーザーを選択してください。';
    }

    if ($('#page_current_id').val() == data) {
        message = '現在のログインユーザーを削除することはできません。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  Validator追加メソッド
 *  -------------------------------------------------------------------------*/
/**
 * Emailアドレスのユニークチェックの追加
 *
 */
$.validator.addMethod('uniqueEmail', function(value, element) {
    result = WNote.ajaxValidateSend(
        '/api/master/system/api-susers/validate_email',
        'POST',
        {
            'id': function(){
                return $('input[name="suser.id"]').val();
            },
            'email': function(){
                return $('input[name="suser.email"]').val();
            }
        }
    );

    return this.optional(element) || (result && result.validate);
}, '入力されたEmailは既に利用されています。別のEmailを入力してください。');

/**
 * 表示名のユニークチェックの追加
 *
 */
$.validator.addMethod('uniqueKname', function(value, element) {
    result = WNote.ajaxValidateSend(
        '/api/master/system/api-susers/validate_kname',
        'POST',
        {
            'id': function(){
                return $('input[name="suser.id"]').val();
            },
            'kname': function(){
                return $('input[name="suser.kname"]').val();
            }
        }
    );

    return this.optional(element) || (result && result.validate);
}, '入力された表示名は既に利用されています。別の表示名を入力してください。');
