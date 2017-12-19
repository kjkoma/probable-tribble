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
    FORM_KEY: "form-classification"
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中の分類データ */
MyPage.selectClassification = {
    classification: {},
    parent: {},
    category: {}
};
MyPage.currentCategoryId = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {
    // 親選択
    $('#parent_id').select2({
        allowClear: true,
        placeholder: 'カテゴリ直下の場合は親グループを空に設定してください。',
        ajax: WNote.ajaxAddSelect2Options({
            url: '/api/master/admin/api-classifications/find-list',
            data: function (params) {
                return {
                    term: params.term,
                    classification_id: MyPage.selectClassification.classification['id']
                };
            },
            processResults: function (data) {
                return {
                    results: (data.data.param) ? data.data.param.classifications : []
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
 *  イベント処理（カテゴリ選択）
 *  -------------------------------------------------------------------------*/
/**
 * ツリー（Element/Parts/side-tree-classifications）用選択イベントのハンドラの実装(カテゴリ選択時)
 */
WNote.Tree.Classification.nodeActivateRootHandler = function(node) {
    // カテゴリの設定
    MyPage.currentCategoryId = node.data.category_id;

    if (WNote.Form.formActionStatus.Current != WNOTE.FORM_STATUS.INIT) {
        WNote.Form.formActionStatus.Before = WNOTE.FORM_STATUS.INIT;
        MyPage.cancel();
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（分類選択）
 *  -------------------------------------------------------------------------*/
/**
 * ツリー（Element/Parts/side-classifications-tree）用選択イベントのハンドラの実装(分類選択時)
 */
WNote.Tree.Classification.nodeActivateItemHandler = function(node) {
    // 分類の取得
    MyPage.getClassification(node.data.classification_id);
}

/**
 * 分類取得
 *
 * @param {integer} classification_id 分類ID
 */
MyPage.getClassification = function(classification_id) {
    // 分類データの取得
    WNote.ajaxFailureMessage = '分類データの読込に失敗しました。再度分類を選択してください。';
    WNote.ajaxSendBasic('/api/master/admin/api-classifications/classification', 'POST',
        { 'classification_id': classification_id },
        false,
        MyPage.getClassificationSuccess
    );
}

/**
 * 分類取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.getClassificationSuccess = function(data) {
    MyPage.selectClassification.classification = data.data.param.classification;
    MyPage.selectClassification.parent   = data.data.param.parent;
    MyPage.selectClassification.category = data.data.param.category;
    WNote.Form.viewMode(MYPAGE.FORM_KEY);
    WNote.hideLoading();
    MyPage.setFormValues();
}

/**
 * 分類データをフォームに表示する
 * 
 */
MyPage.setFormValues = function() {
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.setFormValues(MyPage.selectClassification.classification);
    WNote.Form.setFormValues(MyPage.selectClassification.parent);
    WNote.Form.setFormValues(MyPage.selectClassification.category);
}

/**
 * カテゴリを設定する
 * 
 */
MyPage.setExtendValues = function() {
    var active = WNote.Tree.active();
    if (active.data.type == WNOTE.TREE.TYPES.ROOT) {
        $('#category_id').val(MyPage.currentCategoryId);
    } else if (active.data.type == WNOTE.TREE.TYPES.ITEM) {
        $('#category_id').val(MyPage.selectClassification.category['category_id']);
        var option = new Option(active.title, active.data.classification_id, true, true);
        $('#parent_id').append(option).trigger('change');
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
    if (!MyPage.currentCategoryId) {
        WNote.showErrorMessage('カテゴリが存在しません。カテゴリを先に作成／選択してください。');
        return ;
    }

    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.addMode(MYPAGE.FORM_KEY);
    MyPage.setExtendValues();
    $('#parent_id').prop('disabled', true);
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
    var saveUrl  = '/api/master/admin/api-classifications/';
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
    MyPage.getClassification(data.data.param.classification.id);
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
    if (WNote.Tree.node(current.key)) {
        WNote.Tree.activate(current.key);
    }
}

/**
 * 分類データ保存時の検証を行う
 *
 * @param {string} saveType 保存タイプ（add or edit）
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.saveValidate = function(saveType, data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // kanme 
    options.rules['classification.kname']    = { required: true, maxlength: 20, uniqueKname: true };
    options.messages['classification.kname'] = { required: '表示名を入力してください。', maxlength: '表示名は最大20文字で入力してください。' };

    // name
    options.rules['classification.name']    = { required: true, maxlength: 40 };
    options.messages['classification.name'] = { required: '分類名を入力してください。', maxlength: '分類名は最大40文字で入力してください。' };

    // remarks
    options.rules['classification.remarks']    = { maxlength: 512 };
    options.messages['classification.remarks'] = { maxlength: '備考は最大512文字で入力してください。' };

    // dsts
    options.rules['classification.dsts']    = { required: true };
    options.messages['classification.dsts'] = { required: '使用中／停止を選択してください。' };

    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    if (saveType == "edit" && (!data || data == '')) {
        message = '分類が選択されていません。分類を選択してください。';
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
    WNote.showConfirmMessage('分類を削除すると分類に関連するデータがすべて削除され、復元することができません。本当に削除してもよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.deleteClassification;
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
 * 分類データを削除する
 *
 */
MyPage.deleteClassification = function() {
    // 送信データ作成
    var classification_id = MyPage.selectClassification.classification['id'];

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(classification_id, MyPage.deleteValidate(classification_id))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '分類データの削除に失敗しました。再度削除してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/master/admin/api-classifications/delete', 'POST',
        { 'id': classification_id },
        true,
        MyPage.deleteSuccess
    );
}

/**
 * 分類データ削除時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.deleteValidate = function(data) {
    var message;

    if (!data || data == '') {
        message = '分類が選択されていません。分類を選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  Validator追加メソッド
 *  -------------------------------------------------------------------------*/
/**
 * 分類識別子のユニークチェックの追加
 *
 */
$.validator.addMethod('uniqueKname', function(value, element) {
    result = WNote.ajaxValidateSend(
        '/api/master/admin/api-classifications/validate_kname',
        'POST',
        {
            'id': function(){
                return $('input[name="classification.id"]').val();
            },
            'kname': function(){
                return $('input[name="classification.kname"]').val();
            }
        }
    );

    return this.optional(element) || (result && result.validate);
}, '入力された表示名は既に利用されています。別の表示名を入力してください。');


