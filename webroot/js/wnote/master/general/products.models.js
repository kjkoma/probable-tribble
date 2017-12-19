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
/**
 * 製品ページ内のモデル／型データを処理するライブラリ<br>
 * ※本ライブラリはproducts.index.jsに依存しています。
 *
 */
/** ---------------------------------------------------------------------------
 *  定数
 *  -------------------------------------------------------------------------*/
const MYPAGE.MODELS = {
    FORM_KEY: "form-model"
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ページのルートオブジェクト */
MyPage.Models = {};
/** 選択中のモデル／型データ */
MyPage.selectModel = {
    model: {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // CPU選択
    $('#cpu_id').select2({
        ajax: WNote.ajaxAddSelect2Options({
            url: '/api/master/generic/api-cpus/find-list',
            processResults: function (data) {
                return {
                    results: (data.data.param) ? data.data.param.cpus : []
                };
            }
        })
    });

});

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * モデル／型一覧取得
 *
 * @param {integer} product_id 製品ID
 */
MyPage.getModels = function(product_id) {
    // 製品一覧の取得
    var result = WNote.ajaxValidateSend('/api/master/general/api-models/models-in-product', 'POST',
        { 'product_id': product_id },
    );

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、モデル／型の一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * モデル／型データ取得
 *
 * @param {integer} product_id 製品ID
 */
MyPage.getModel = function(product_id) {
    // 製品データの取得
    WNote.ajaxFailureMessage = 'モデル／型データの読込に失敗しました。再度製品を選択してください。';
    WNote.ajaxSendBasic('/api/master/general/api-products/product', 'POST',
        { 'product_id': product_id },
        false,
        MyPage.getProductSuccess
    );
}

/**
 * モデル／型取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.getModelSuccess = function(data) {
    MyPage.selectModel.model = data.data.param.model;
    WNote.Form.viewMode(MYPAGE.MODELS.FORM_KEY);
    WNote.hideLoading();
    MyPage.Models.setFormValues();
}

/**
 * モデル／型データをフォームに表示する
 * 
 */
MyPage.Models.setFormValues = function() {
    WNote.Form.clearFormValues(MYPAGE.MODELS.FORM_KEY);
    WNote.Form.setFormValues(MyPage.selectModel.model);
}

/**
 * 製品を設定する
 * 
 */
MyPage.Models.setExtendValues = function() {
    var active = WNote.Tree.active();
    if (active.data.type == WNOTE.TREE.TYPES.BRANCH) {
        var option = new Option(active.title, active.data.classification_id, true, true);
        $('#classification_id').append(option).trigger('change');
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（編集画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 編集ボタンクリックイベントのハンドラの実装
 */
MyPage.Models.edit = function() {
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
        WNote.showErrorMessage('製品を追加する分類を選択してください。');
        return ;
    }

    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.addMode(MYPAGE.FORM_KEY);
    MyPage.setExtendValues();
    $('#classification_id').prop('disabled', true);
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
    var saveUrl  = '/api/master/general/api-products/';
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
    MyPage.getProduct(data.data.param.product.id);
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
 * 製品データ保存時の検証を行う
 *
 * @param {string} saveType 保存タイプ（add or edit）
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.saveValidate = function(saveType, data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // kname
    options.rules['product.kname']    = { required: true, maxlength: 30 };
    options.messages['product.kname'] = { required: '表示名を入力してください。', maxlength: '表示名は最大30文字で入力してください。' };

    // name
    options.rules['product.name']    = { required: true, maxlength: 80 };
    options.messages['product.fname'] = { required: '製品名（正式名称）を入力してください。', maxlength: '製品名（正式名称）は最大80文字で入力してください。' };

    // maker_id
    options.rules['product.maker_id']    = { required: true };
    options.messages['product.maker_id'] = { required: '製造元を選択してください。' };

    // psts
    options.rules['product.psts']    = { required: true };
    options.messages['product.psts'] = { required: '製品ステータスを選択してください。' };

    // sales_start
    options.rules['product.sales_start']    = { date: true };
    options.messages['product.sales_start'] = { date: '有効な販売開始日をyyyy/mm/ddの形式で入力してください。' };

    // sales_start
    options.rules['product.sales_end']    = { date: true };
    options.messages['product.sales_end'] = { date: '有効な販売終了日をyyyy/mm/ddの形式で入力してください。' };

    // remarks
    options.rules['product.remarks']    = { maxlength: 512 };
    options.messages['product.remarks'] = { maxlength: '備考は最大512文字で入力してください。' };

    // dsts
    options.rules['product.dsts']    = { required: true };
    options.messages['product.dsts'] = { required: '使用中／停止を選択してください。' };

    // classification_id
    options.rules['product.classification_id']    = { required: true };
    options.messages['product.classification_id'] = { required: '製品分類を選択してください。' };

    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    if (saveType == "edit" && (!data || data == '')) {
        message = '製品が選択されていません。製品を選択してください。';
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
    WNote.showConfirmMessage('製品を削除すると製品に関連するデータがすべて削除され、復元することができません。本当に削除してもよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.deleteProduct;
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
MyPage.deleteProduct = function() {
    // 送信データ作成
    var product_id = MyPage.selectProduct.product['id'];

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(product_id, MyPage.deleteValidate(product_id))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '製品データの削除に失敗しました。再度削除してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/master/general/api-products/delete', 'POST',
        { 'id': product_id },
        true,
        MyPage.deleteSuccess
    );
}

/**
 * 製品データ削除時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.deleteValidate = function(data) {
    var message;

    if (!data || data == '') {
        message = '製品が選択されていません。製品を選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}


