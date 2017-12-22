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
    FORM_KEY: "form-user"
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中のユーザーデータ */
MyPage.selectUser = {
    user: {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {
    // 組織選択
    $('#organization_id').select2({
        ajax: WNote.ajaxAddSelect2Options({
            url: '/api/master/admin/api-organizations/find-list',
            processResults: function (data) {
                return {
                    results: (data.data.param) ? data.data.param.organizations : []
                };
            }
        })
    });

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'edit'   , MyPage.edit);
    WNote.registerEvent('click', 'add'    , MyPage.add);
    WNote.registerEvent('click', 'cancel' , MyPage.cancel);
    WNote.registerEvent('click', 'save'   , MyPage.save);
    WNote.registerEvent('click', 'delete' , MyPage.delete);

});

/** ---------------------------------------------------------------------------
 *  イベント処理（資産管理会社変更）
 *  -------------------------------------------------------------------------*/
/** 資産管理会社変更時のイベントハンドラの実装 */
WNote.Tree.User.afterChangeCustomerHandler = function(event) {
    WNote.Form.formActionStatus.Before = WNOTE.FORM_STATUS.INIT;
    MyPage.cancel();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（資産管理組織選択）
 *  -------------------------------------------------------------------------*/
/**
 * ツリー（Element/Parts/side-tree-organizations）用選択イベントのハンドラの実装(ユーザー選択時)
 */
WNote.Tree.User.nodeActivateValueHandler = function(node) {
    // ユーザーの取得
    MyPage.getUser(node.data.user_id);
}

/**
 * ユーザー取得
 *
 * @param {integer} user_id ユーザーID
 */
MyPage.getUser = function(user_id) {
    // ユーザーデータの取得
    WNote.ajaxFailureMessage = 'ユーザーデータの読込に失敗しました。再度ユーザーを選択してください。';
    WNote.ajaxSendBasic('/api/master/admin/api-users/user', 'POST',
        { 'user_id': user_id },
        false,
        MyPage.getUserSuccess
    );
}

/**
 * ユーザー取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.getUserSuccess = function(data) {
    MyPage.selectUser.user = data.data.param.user;
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
    WNote.Form.setFormValues(MyPage.selectUser.user);
}

/**
 * 資産管理組織を設定する
 * 
 */
MyPage.setExtendValues = function() {
    var active = WNote.Tree.active();
    if (active.data.type == WNOTE.TREE.TYPES.BRANCH) {
        var option = new Option(active.title, active.data.organization_id, true, true);
        $('#organization_id').append(option).trigger('change');
    }
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
    var active = WNote.Tree.active();
    if (active.data.type != WNOTE.TREE.TYPES.BRANCH) {
        WNote.showErrorMessage('ユーザーを追加する組織を選択してください。');
        return ;
    }

    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.addMode(MYPAGE.FORM_KEY);
    MyPage.setExtendValues();
    $('#organization_id').prop('disabled', true);
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
    var saveUrl  = '/api/master/admin/api-users/';
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
    MyPage.getUser(data.data.param.user.id);
    WNote.ajaxSuccessHandler(data, '入力された内容を登録しました。');
    var current = WNote.Tree.active();
    WNote.Tree.activateParent();
    WNote.Tree.expand(current.key);
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
    var current = WNote.Tree.active();
    WNote.Tree.activateParent();
    if (current) {
        WNote.Tree.expand(current.key);
    }
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

    // sanme 
    options.rules['user.sname']    = { required: true, maxlength: 8 };
    options.messages['user.sname'] = { required: '姓を入力してください。', maxlength: '姓は最大8文字で入力してください。' };

    // fanme 
    options.rules['user.fname']    = { required: true, maxlength: 8 };
    options.messages['user.fname'] = { required: '名を入力してください。', maxlength: '名は最大8文字で入力してください。' };

    // email
    options.rules['user.email']    = { maxlength: 255 };
    options.messages['user.name'] = { maxlength: '資産管理組織名は最大40文字で入力してください。', email: 'Emailアドレスの形式が違います。' };

    // employee_no
    options.rules['user.employee_no']    = { maxlength: 20 };
    options.messages['user.employee_no'] = { maxlength: '社員番号は最大20文字で入力してください。' };

    // remarks
    options.rules['user.remarks']    = { maxlength: 512 };
    options.messages['user.remarks'] = { maxlength: '備考は最大512文字で入力してください。' };

    // dsts
    options.rules['user.dsts']    = { required: true };
    options.messages['user.dsts'] = { required: '使用中／停止を選択してください。' };

    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    if (saveType == "edit" && (!data || data == '')) {
        message = 'ユーザーが選択されていません。ユーザーを選択してください。';
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
    WNote.showConfirmMessage('ユーザーを削除するとユーザーに関連するデータがすべて削除され、復元することができません。本当に削除してもよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.deleteUser;
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
    WNote.Tree.activateParent();
}

/**
 * 資産管理組織データを削除する
 *
 */
MyPage.deleteUser = function() {
    // 送信データ作成
    var user_id = MyPage.selectUser.user['id'];

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(user_id, MyPage.deleteValidate(user_id))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = 'ユーザーデータの削除に失敗しました。再度削除してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/master/admin/api-users/delete', 'POST',
        { 'id': user_id },
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

    return WNote.Form.validateResultSet(message);
}


