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
const MYPAGE_MODELS = {
    FORM_KEY: "form-model",
    PREFIX:   "model_"
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ページのルートオブジェクト */
MyPage.Models = {};
/** 選択中のモデル／型データ */
MyPage.Models.selectModel = {
    model: {}
};
/** モデル一覧 */
MyPage.Models.datatable;

/** フォーム操作用 */
MyPage.Models.form;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // フォーム操作用インスタンスの作成
    MyPage.Models.form = new WNote.Lib.Form({
        'add'  : 'add-model-actions',
        'edit' : 'edit-model-actions',
        'view' : 'view-model-actions',
        'delete': 'delete-model-actions'
    });

    // CPU選択
    $('#model_cpu_id').select2({
        ajax: WNote.ajaxAddSelect2Options({
            url: '/api/master/general/api-cpus/find-list',
            processResults: function (data) {
                return {
                    results: (data.data.param) ? data.data.param.cpus : []
                };
            }
        })
    });

    // データテーブル初期化
    MyPage.Models.datatable = $('#model-datatable').DataTable({
        paging    : false,
        scrollY   : 150,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'kname' },
            { data: 'cpu' },
            { data: 'memory' },
            { data: 'storage' }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#model-datatable tbody').on('click', 'tr', MyPage.Models.selectedModelHandler);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'edit-model'   , MyPage.Models.edit);
    WNote.registerEvent('click', 'add-model'    , MyPage.Models.add);
    WNote.registerEvent('click', 'cancel-model' , MyPage.Models.cancel);
    WNote.registerEvent('click', 'save-model'   , MyPage.Models.save);
    WNote.registerEvent('click', 'delete-model' , MyPage.Models.delete);

});

/** ---------------------------------------------------------------------------
 *  イベント処理（タブ切替）
 *  -------------------------------------------------------------------------*/
/**
 * モデルタブ内コンテンツの表示イベント処理(実装)
 */
MyPage.showModelHandler = function(event) {
    // 全製品データの操作ボタンを非表示
    $('#product-actions').addClass('hidden');

    // モデル一覧を表示
    MyPage.Models.showModels();
}
/**
 * モデルタブ内コンテンツの非表示イベント処理(実装)
 */
MyPage.hideModelHandler = function(event) {
    // 表示状態のキャンセル
    MyPage.Models.form.before = WNOTE.FORM_STATUS.INIT;
    MyPage.Models.cancel();

    // 全製品データの操作ボタンを再表示
    $('#product-actions').removeClass('hidden');
}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * モデル／型一覧取得
 *
 * @param {integer} product_id 製品ID
 */
MyPage.Models.getModels = function(product_id) {
    // 製品一覧の取得
    var result = WNote.ajaxValidateSend('/api/master/general/api-product-models/models-in-product', 'POST',
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
 * モデル／型一覧表示
 *
 */
MyPage.Models.showModels = function() {
    var result = MyPage.Models.getModels(MyPage.selectProduct.product['id']);
    MyPage.Models.datatable
        .clear()
        .rows.add(result.models)
        .draw();
}

/**
 * モデル／型データ取得
 *
 * @param {integer} model_id モデルID
 */
MyPage.Models.getModel = function(model_id) {
    // 製品データの取得
    WNote.ajaxFailureMessage = 'モデル／型データの読込に失敗しました。再度製品を選択してください。';
    WNote.ajaxSendBasic('/api/master/general/api-product-models/model', 'POST',
        { 'model_id': model_id },
        false,
        MyPage.Models.getModelSuccess
    );
}

/**
 * モデル／型取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.Models.getModelSuccess = function(data) {
    MyPage.Models.selectModel.model = data.data.param.model;
    MyPage.Models.form.viewMode(MYPAGE_MODELS.FORM_KEY);
    WNote.hideLoading();
    MyPage.Models.setFormValues();
}

/**
 * モデル／型データをフォームに表示する
 * 
 */
MyPage.Models.setFormValues = function() {
    WNote.Form.clearFormValues(MYPAGE_MODELS.FORM_KEY);
    WNote.Form.setFormValuesWithPrefix(MyPage.Models.selectModel.model, MYPAGE_MODELS.PREFIX);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（モデル選択）
 *  -------------------------------------------------------------------------*/
MyPage.Models.selectedModelHandler = function() {
    var selected = MyPage.Models.datatable.row( this ).data();
    MyPage.Models.getModel(selected.id);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（編集画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 編集ボタンクリックイベントのハンドラの実装
 */
MyPage.Models.edit = function() {
    MyPage.Models.form.editMode(MYPAGE_MODELS.FORM_KEY);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（追加画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 追加ボタンクリックイベントのハンドラの実装
 */
MyPage.Models.add = function() {
    WNote.Form.clearFormValues(MYPAGE_MODELS.FORM_KEY);
    MyPage.Models.form.addMode(MYPAGE_MODELS.FORM_KEY);
    MyPage.setExtendValues();
    $('#model_product_id').val(MyPage.selectProduct.product['id']);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（キャンセル）
 *  -------------------------------------------------------------------------*/
/**
 * キャンセルボタンクリックイベントのハンドラの実装
 */
MyPage.Models.cancel = function() {
    MyPage.Models.form.validateClear();

    if (MyPage.Models.form.before == WNOTE.FORM_STATUS.INIT) {
        WNote.Form.clearFormValues(MYPAGE_MODELS.FORM_KEY);
        MyPage.Models.form.initMode(MYPAGE_MODELS.FORM_KEY);
    } else {
        MyPage.setFormValues();
        MyPage.Models.form.viewMode(MYPAGE_MODELS.FORM_KEY);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリックイベントのハンドラの実装
 */
MyPage.Models.save = function() {
    var saveUrl  = '/api/master/general/api-product-models/';
    var saveType = 'add';
    var successHandler = MyPage.Models.saveAddSuccess;

    if (MyPage.Models.form.current == WNOTE.FORM_STATUS.EDIT) {
        saveType       = 'edit';
        successHandler = MyPage.Models.saveEditSuccess;
    }

    // 送信データ作成
    var data = WNote.Form.createAjaxData(MYPAGE_MODELS.FORM_KEY);

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.Models.saveValidate(saveType, data))) {
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
MyPage.Models.saveAddSuccess = function(data) {
WNote.log(data); // debug
    MyPage.Models.getModel(data.data.param.model.id);
    WNote.ajaxSuccessHandler(data, '入力された内容を登録しました。');
    MyPage.Models.showModels();
}
/**
 * 保存ボタンクリック成功時のハンドラの実装（編集時）
 *
 * @param {object} data レスポンスデータ
 */
MyPage.Models.saveEditSuccess = function(data) {
WNote.log(data); // debug
    MyPage.Models.form.viewMode(MYPAGE_MODELS.FORM_KEY);
    MyPage.Models.form.validateClear();
    WNote.ajaxSuccessHandler(data, '入力された内容を保存しました。');
}

/**
 * モデルデータ保存時の検証を行う
 *
 * @param {string} saveType 保存タイプ（add or edit）
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.Models.saveValidate = function(saveType, data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // kname
    options.rules['model.kname']    = { required: true, maxlength: 30 };
    options.messages['model.kname'] = { required: '表示名を入力してください。', maxlength: '表示名は最大30文字で入力してください。' };

    // name
    options.rules['model.name']    = { required: true, maxlength: 120 };
    options.messages['model.fname'] = { required: 'モデル・型名（正式名称）を入力してください。', maxlength: '製品名（正式名称）は最大120文字で入力してください。' };

    // maker_id
    options.rules['model.maker_id']    = { required: true };
    options.messages['model.maker_id'] = { required: '製造元を選択してください。' };

    // psts
    options.rules['model.psts']    = { required: true };
    options.messages['model.psts'] = { required: '製品ステータスを選択してください。' };

    // sales_start
    options.rules['model.sales_start']    = { date: true, dateFormat: true };
    options.messages['model.sales_start'] = { date: '有効な販売開始日をyyyy/mm/ddの形式で入力してください。', dateFormat: '日付はyyyy/mm/dd形式で入力してください。' };

    // sales_start
    options.rules['model.sales_end']    = { date: true, dateFormat: true };
    options.messages['model.sales_end'] = { date: '有効な販売終了日をyyyy/mm/ddの形式で入力してください。', dateFormat: '日付はyyyy/mm/dd形式で入力してください。' };

    // memory
    options.rules['model.memory']    = { number: true };
    options.messages['model.memory'] = { number: 'メモリ容量は数値（半角）で入力してください。' };

    // storage_vol
    options.rules['model.storage_vol']    = { number: true };
    options.messages['model.storage_vol'] = { number: 'ストレージ容量は数値（半角）で入力してください。' };

    // maked_date
    options.rules['model.maked_date']    = { date: true, dateFormat: true };
    options.messages['model.maked_date'] = { date: '有効な製造日をyyyy/mm/ddの形式で入力してください。', dateFormat: '日付はyyyy/mm/dd形式で入力してください。' };

    // remarks
    options.rules['model.remarks']    = { maxlength: 512 };
    options.messages['model.remarks'] = { maxlength: '備考は最大512文字で入力してください。' };

    // dsts
    options.rules['model.dsts']    = { required: true };
    options.messages['model.dsts'] = { required: '使用中／停止を選択してください。' };

    MyPage.Models.form.validator = $('#' + MYPAGE_MODELS.FORM_KEY).validate(options);
    MyPage.Models.form.validator.form();
    if (!MyPage.Models.form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    if (saveType == "edit" && (!data || data == '')) {
        message = 'モデルが選択されていません。モデルを選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（削除）
 *  -------------------------------------------------------------------------*/
/**
 * 削除ボタンクリックイベントのハンドラの実装
 */
MyPage.Models.delete = function() {
    WNote.showConfirmMessage('モデルを削除するとモデルに関連するデータがすべて削除され、復元することができません。本当に削除してもよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.Models.deleteModel;
}
/**
 * 削除ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.Models.deleteSuccess = function(data) {
WNote.log(data); // debug
    WNote.Form.clearFormValues(MYPAGE_MODELS.FORM_KEY);
    MyPage.Models.form.initMode(MYPAGE_MODELS.FORM_KEY);
    WNote.ajaxSuccessHandler(data, '選択されたデータが正常に削除されました。');
    MyPage.Models.showModels();
}

/**
 * モデルデータを削除する
 *
 */
MyPage.Models.deleteModel = function() {
    // 送信データ作成
    var model_id = MyPage.Models.selectModel.model['id'];

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(model_id, MyPage.Models.deleteValidate(model_id))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = 'モデルデータの削除に失敗しました。再度削除してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/master/general/api-product-models/delete', 'POST',
        { 'id': model_id },
        true,
        MyPage.Models.deleteSuccess
    );
}

/**
 * モデルデータ削除時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.Models.deleteValidate = function(data) {
    var message;

    if (!data || data == '') {
        message = 'モデルが選択されていません。モデルを選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}


