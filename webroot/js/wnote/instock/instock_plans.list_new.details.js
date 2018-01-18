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
 * 入庫予定（新規）ページ内の入庫予定詳細を処理するライブラリ<br>
 * ※本ライブラリはinstock_plans.list_new.jsに依存しています。
 *
 */
/** ---------------------------------------------------------------------------
 *  定数
 *  -------------------------------------------------------------------------*/
const MYPAGE_DETAILS = {
    FORM_KEY : 'form-detail',
    PREFIX   : {
        DETAIL : 'detail_',
        BACK   : 'back_'
    },
    CONTENTS : '#detail-contents',
    ID : {
        BACK      : '#input-back-id',
        PLAN_COUNT: '#detail_plan_count',
        LIMIT_DATE: '#detail_support_limit_date'
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ページのルートオブジェクト */
MyPage.Details = {};

/** 選択中の入庫予定詳細データ */
MyPage.Details.selectDetail = {
    detail: {},
    back  : {},
    instockType: null
};

/** 詳細一覧 */
MyPage.Details.datatable;

/** フォーム操作用 */
MyPage.Details.form;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 入庫タイプ設定
    MyPage.Details.selectDetail.instockType = $('[name="instock_type"]').val();

    // フォーム操作用インスタンスの作成
    MyPage.Details.form = new WNote.Lib.Form({
        'add'   : 'add-detail-actions',
        'edit'  : 'edit-detail-actions',
        'view'  : 'view-detail-actions',
        'delete': 'delete-detail-actions'
    });

    // データテーブル初期化
    MyPage.Details.datatable = $('#detail-datatable').DataTable({
        paging    : false,
        scrollY   : 150,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'category_name'       , width: '15%' },
            { data: 'classification_name' , width: '20%' },
            { data: 'product_name'        , width: '20%' },
            { data: 'model_name'          , width: '20%' },
            { data: 'plan_count'          , width: '8%' },
            { data: 'instock_count'       , width: '8%' },
            { data: 'detail_sts_name'     , width: '9%' }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#detail-datatable tbody').on('click', 'tr', MyPage.Details.selectedDetailHandler);

    /** select2 登録 */
    WNote.Select2.classification('#detail_classification_id', null                       , false, '分類入力・選択');
    WNote.Select2.product('#detail_product_id'              , '#detail_classification_id', false, '製品入力・選択');
    WNote.Select2.model('#detail_product_model_id'          , '#detail_product_id'       , false, 'モデル入力・選択');
    WNote.Select2.organization('#back_req_organization_id'  , null                       , false, '返却者（組織）入力・選択');
    WNote.Select2.user('#back_req_user_id'                  , '#back_req_organization_id', false, '返却者（ユーザー）入力・選択');
    WNote.Select2.sUser('#back_rcv_suser_id'                , null                       , false, '受付者入力・選択');

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'edit-detail'   , MyPage.Details.edit);
    WNote.registerEvent('click', 'add-detail'    , MyPage.Details.add);
    WNote.registerEvent('click', 'cancel-detail' , MyPage.Details.cancel);
    WNote.registerEvent('click', 'save-detail'   , MyPage.Details.save);
    WNote.registerEvent('click', 'delete-detail' , MyPage.Details.delete);

});

/** ---------------------------------------------------------------------------
 *  入庫予定ライブラリ（instock_plans.list_new.js）内宣言の実装
 *  -------------------------------------------------------------------------*/
/**
 * 入庫予定詳細表示（入庫予定ライブラリ内の宣言の実装）
 *
 * @param {string} plan_id 入庫予定ID
 */
MyPage.showDetailTable = function(plan_id) {
    // 表示状態のキャンセル
    MyPage.Details.form.before = WNOTE.FORM_STATUS.INIT;
    MyPage.Details.cancel();

    result = MyPage.Details.getDetails(plan_id);
    if (result) {
        var details = (result && result.details) ? result.details : [];
        MyPage.Details.showDetails(details);
    }
}

/**
 * 入庫予定詳細クリア（入庫予定ライブラリ内の宣言の実装）
 *
 * @param {string} plan_id 入庫予定ID
 */
MyPage.clearDetailTable = function(plan_id) {
    // 表示状態のキャンセル
    MyPage.Details.form.before = WNOTE.FORM_STATUS.INIT;
    MyPage.Details.cancel();
}

/**
 * 入庫予定詳細表示（入庫予定ライブラリ内の宣言の実装）
 *
 */
MyPage.showDetails = function(plan_id) {
    $(MYPAGE_DETAILS.CONTENTS).removeClass('hidden');
}

/**
 * 入庫予定詳細非表示（入庫予定ライブラリ内の宣言の実装）
 *
 */
MyPage.hideDetails = function(plan_id) {
    $(MYPAGE_DETAILS.CONTENTS).addClass('hidden');
}

/**
 * 入庫区分 = 新規選択時制御
 *
 */
MyPage.selectInputKbnNewHandler = function() {
    $(MYPAGE_DETAILS.ID.BACK).addClass('hidden');
    $(MYPAGE_DETAILS.ID.PLAN_COUNT).prop('disabled', false);
    $(MYPAGE_DETAILS.ID.LIMIT_DATE).prop('disabled', false);
}

/**
 * 入庫区分 = 返却選択時制御
 *
 */
MyPage.selectInputKbnBackHandler = function() {
    $(MYPAGE_DETAILS.ID.BACK).removeClass('hidden');
    $(MYPAGE_DETAILS.ID.PLAN_COUNT).prop('disabled', true);
    $(MYPAGE_DETAILS.ID.LIMIT_DATE).prop('disabled', true);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 入庫予定詳細一覧取得
 *
 * @param {integer} plan_id 入庫予定ID
 */
MyPage.Details.getDetails = function(plan_id) {
    // 入庫予定詳細一覧の取得
    var result = WNote.ajaxValidateSend('/api/instock/api-instock-plan-details/details', 'POST',　{
        'plan_id': plan_id
    });

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、入庫予定の詳細一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 入庫予定詳細一覧表示
 *
 */
MyPage.Details.showDetails = function() {
    var result = MyPage.Details.getDetails(MyPage.selectPlan.plan['id']);
    MyPage.Details.datatable
        .clear()
        .rows.add(result.details)
        .draw();
}

/**
 * 入庫予定詳細データ取得
 *
 * @param {integer} detail_id 入庫予定詳細ID
 */
MyPage.Details.getDetail = function(detail_id) {
    // 製品データの取得
    WNote.ajaxFailureMessage = '入庫予定詳細データの読込に失敗しました。再度入庫予定詳細を選択してください。';
    WNote.ajaxSendBasic('/api/instock/api-instock-plan-details/detail', 'POST',
        { 'detail_id': detail_id },
        false,
        MyPage.Details.getDetailSuccess
    );
}

/**
 * 入庫予定詳細取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.Details.getDetailSuccess = function(data) {
    MyPage.Details.selectDetail.detail = data.data.param.detail;
    MyPage.Details.selectDetail.back = data.data.param.assetback;
    MyPage.Details.form.viewMode(MYPAGE_DETAILS.FORM_KEY);
    WNote.hideLoading();
    MyPage.Details.setFormValues();
}

/**
 * 入庫予定詳細データをフォームに表示する
 * 
 */
MyPage.Details.setFormValues = function() {
    WNote.Form.clearFormValues(MYPAGE_DETAILS.FORM_KEY);
    WNote.Form.setFormValuesWithPrefix(MyPage.Details.selectDetail.detail, MYPAGE_DETAILS.PREFIX.DETAIL);
    WNote.Form.setFormValuesWithPrefix(MyPage.Details.selectDetail.back, MYPAGE_DETAILS.PREFIX.BACK);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（モデル選択）
 *  -------------------------------------------------------------------------*/
MyPage.Details.selectedDetailHandler = function() {
    var selected = MyPage.Details.datatable.row( this ).data();
    if (selected) {
        MyPage.Details.getDetail(selected.id);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（編集画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 編集ボタンクリックイベントのハンドラの実装
 */
MyPage.Details.edit = function() {
    MyPage.Details.form.editMode(MYPAGE_DETAILS.FORM_KEY);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（追加画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 追加ボタンクリックイベントのハンドラの実装
 */
MyPage.Details.add = function() {
    WNote.Form.clearFormValues(MYPAGE_DETAILS.FORM_KEY);
    MyPage.Details.form.addMode(MYPAGE_DETAILS.FORM_KEY);
    MyPage.controlInputKbn();
    $('#detail_instock_plan_id').val(MyPage.selectPlan.plan.id);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（キャンセル）
 *  -------------------------------------------------------------------------*/
/**
 * キャンセルボタンクリックイベントのハンドラの実装
 */
MyPage.Details.cancel = function() {
    MyPage.Details.form.validateClear();

    if (MyPage.Details.form.before == WNOTE.FORM_STATUS.INIT) {
        WNote.Form.clearFormValues(MYPAGE_DETAILS.FORM_KEY);
        MyPage.Details.form.initMode(MYPAGE_DETAILS.FORM_KEY);
    } else {
        MyPage.setFormValues();
        MyPage.Details.form.viewMode(MYPAGE_DETAILS.FORM_KEY);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリックイベントのハンドラの実装
 */
MyPage.Details.save = function() {
    var saveUrl  = '/api/instock/api-instock-plan-details/';
    var saveType = 'add';
    var successHandler = MyPage.Details.saveAddSuccess;

    if (MyPage.Details.form.current == WNOTE.FORM_STATUS.EDIT) {
        saveType       = 'edit';
        successHandler = MyPage.Details.saveEditSuccess;
    }

    // 送信データ作成
    var data = WNote.Form.createAjaxData(MYPAGE_DETAILS.FORM_KEY);

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.Details.saveValidate(saveType, data))) {
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
MyPage.Details.saveAddSuccess = function(data) {
WNote.log(data); // debug
    MyPage.Details.getDetail(data.data.param.detail.id);
    WNote.ajaxSuccessHandler(data, '入力された内容を登録しました。');
    MyPage.Details.showDetails();
}
/**
 * 保存ボタンクリック成功時のハンドラの実装（編集時）
 *
 * @param {object} data レスポンスデータ
 */
MyPage.Details.saveEditSuccess = function(data) {
WNote.log(data); // debug
    MyPage.Details.form.viewMode(MYPAGE_DETAILS.FORM_KEY);
    MyPage.Details.form.validateClear();
    WNote.ajaxSuccessHandler(data, '入力された内容を保存しました。');
    MyPage.Details.showDetails();
}

/**
 * 入庫予定詳細データ保存時の検証を行う
 *
 * @param {string} saveType 保存タイプ（add or edit）
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.Details.saveValidate = function(saveType, data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    if (saveType == 'edit') {
        // 入庫済みチェック
        var result = MyPage.Details.validateAlreadyInstock(data.detail.id);
        if (result) {
            return WNote.Form.validateResultSet('すでに入庫が行われているため変更することはできません。');
        }
    }

    // classification_id
    options.rules['detail.classification_id']    = { required: true };
    options.messages['detail.classification_id'] = { required: '分類を選択してください。'};

    // product_id
    options.rules['detail.product_id']    = { required: true, validateProduct: true };
    options.messages['detail.product_id'] = { required: '製品を選択してください。'};

    // product_model_id
    options.rules['detail.product_model_id'] = { validateModel: true };

    // plan_count
    options.rules['detail.plan_count']    = { required: true, number: true, validatePlanCount: true };
    options.messages['detail.plan_count'] = { required: '予定数量を入力してください。', number: '予定数量は数値（半角）で入力してください。' };

    // support_limit_date
    options.rules['detail.support_limit_date']    = { date: true };
    options.messages['detail.support_limit_date'] = { date: '有効な保守期限日をyyyy/mm/ddの形式で入力してください。' };

    // 資産返却時
    if (MyPage.instockKbn.current != MyPage.instockKbn.new) {
        // req_user_id
        options.rules['back.req_user_id']    = { required: true };
        options.messages['back.req_user_id'] = { required: '返却者を選択してください。'};

        // rcv_suser_id
        options.rules['back.rcv_suser_id']    = { required: true };
        options.messages['back.rcv_suser_id'] = { required: '受付者を選択してください。'};

        // asset_no
        options.rules['back.asset_no']    = { required: MyPage.validateSerialAndAssetNo, maxlength: 60, validateAssetNo: true };
        options.messages['back.asset_no'] = { required: '資産管理番号、シリアル番号のいずれかを入力してください。', maxlength: '資産管理番号は最大60文字で入力してください。' };

        // serial_no
        options.rules['back.serial_no']    = { required: MyPage.validateSerialAndAssetNo, maxlength: 120, validateSerialNo: true };
        options.messages['back.serial_no'] = { required: '資産管理番号、シリアル番号のいずれかを入力してください。', maxlength: 'シリアル番号は最大120文字で入力してください。' };

        // assetback_reason
        options.rules['back.assetback_reason']    = { required: true, maxlength: 2048 };
        options.messages['back.assetback_reason'] = { required: '返却理由を入力してください。', maxlength: '返却理由は2048文字以内で入力してください。'};
    }


    MyPage.Details.form.validator = $('#' + MYPAGE_DETAILS.FORM_KEY).validate(options);
    MyPage.Details.form.validator.form();
    if (!MyPage.Details.form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    if (saveType == 'edit' && (!data || data == '')) {
        message = '入庫予定詳細が選択されていません。入庫予定詳細を選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（削除）
 *  -------------------------------------------------------------------------*/
/**
 * 削除ボタンクリックイベントのハンドラの実装
 */
MyPage.Details.delete = function() {
    WNote.showConfirmMessage('入庫予定詳細を削除すると入庫予定詳細に関連するデータがすべて削除され、復元することができません。本当に削除してもよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.Details.deleteDetail;
}
/**
 * 削除ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.Details.deleteSuccess = function(data) {
WNote.log(data); // debug
    WNote.Form.clearFormValues(MYPAGE_DETAILS.FORM_KEY);
    MyPage.Details.form.initMode(MYPAGE_DETAILS.FORM_KEY);
    WNote.ajaxSuccessHandler(data, '選択されたデータが正常に削除されました。');
    MyPage.Details.showDetails();
}

/**
 * 入庫予定詳細データを削除する
 *
 */
MyPage.Details.deleteDetail = function() {
    // 送信データ作成
    var detail_id = MyPage.Details.selectDetail.detail['id'];

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(detail_id, MyPage.Details.deleteValidate(detail_id))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '入庫予定詳細データの削除に失敗しました。再度削除してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/instock/api-instock-plan-details/delete', 'POST',
        { 'id': detail_id },
        true,
        MyPage.Details.deleteSuccess
    );
}

/**
 * 入庫予定詳細データ削除時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.Details.deleteValidate = function(data) {
    var message;

    // 入庫済みチェック
    var result = MyPage.Details.validateNotInstock(data);
    if (!result) {
        message = 'すでに入庫が行われているため削除することはできません。';
    }

    if (!data || data == '') {
        message = '入庫予定詳細が選択されていません。入庫予定詳細を選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  Validateメソッド
 *  -------------------------------------------------------------------------*/

/**
 * 入庫済（一部入庫含む）かどうかを検証する
 *
 * @param {number} detailId 入庫詳細ID
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.Details.validateAlreadyInstock = function(detailId) {
    var message;

    // 入庫数量チェック
    result = WNote.ajaxValidateSend(
        '/api/instock/api-instock-plan-details/validate_already_instock',
        'POST',
        {
            'id': detailId
        }
    );

    return (result && result.validate);
}

/**
 * 未入庫どうかを検証する
 *
 * @param {number} detailId 入庫詳細ID
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.Details.validateNotInstock = function(detailId) {
    // 入庫数量チェック
    result = WNote.ajaxValidateSend(
        '/api/instock/api-instock-plan-details/validate_already_instock',
        'POST',
        {
            'id': detailId
        }
    );

    return (result && !result.validate); // validateはtrueが入庫済なので反転する
}

/**
 * 返却の場合の資産管理番号とシリアル番号の相関チェック
 *
 * @result {boolean} 検証結果（true: 入力あり/false: 入力なし）
 */
MyPage.validateSerialAndAssetNo = function(element) {
    var prefix = $(element).attr('name').split('.')[0];
    var assetNo  = $.trim($('input[name="'+ prefix +'.asset_no"]').val());
    var serialNo = $.trim($('input[name="'+ prefix +'.serial_no"]').val());

    // いずれか一方の入力のみ許可
    if (assetNo != '' && serialNo != '') return true;

    return false;
}

/** ---------------------------------------------------------------------------
 *  Validator追加メソッド
 *  -------------------------------------------------------------------------*/
/**
 * 分類と製品の関連チェックの追加
 *
 */
$.validator.addMethod('validateProduct', function(value, element) {
    result = WNote.ajaxValidateSend(
        '/api/master/general/api-products/validate_product_and_classification',
        'POST',
        {
            'product_id': function(){
                return $('[name="detail.product_id"]').val();
            },
            'classification_id': function(){
                return $('[name="detail.classification_id"]').val();
            }
        }
    );

    return this.optional(element) || (result && result.validate);
}, '選択された製品は指定されている分類に含まれていません。分類、または、製品を選択しなおしてください。');

/**
 * モデルと製品の関連チェックの追加
 *
 */
$.validator.addMethod('validateModel', function(value, element) {
    result = WNote.ajaxValidateSend(
        '/api/master/general/api-product-models/validate_model_and_product',
        'POST',
        {
            'model_id': function(){
                return $('[name="detail.product_model_id"]').val();
            },
            'product_id': function(){
                return $('[name="detail.product_id"]').val();
            }
        }
    );

    return this.optional(element) || (result && result.validate);
}, '選択されたモデル／型は指定されている製品に含まれていません。製品、または、モデル／型を選択しなおしてください。');

/**
 * 予定数量と実入庫数量の関連チェックの追加（※予定数量が実入庫数量を下回る入力を拒否する）
 *
 */
$.validator.addMethod('validatePlanCount', function(value, element) {
    // 追加時はチェックしない
    if ($('input[name="detail.id"]').val().length === 0) {
        return true;
    }

    result = WNote.ajaxValidateSend(
        '/api/instock/api-instock-plan-details/validate_plan_count',
        'POST',
        {
            'id': function(){
                return $('input[name="detail.id"]').val();
            },
            'plan_count': function(){
                return $('input[name="detail.plan_count"]').val();
            }
        }
    );

    return this.optional(element) || (result && result.validate);
}, '入力された予定数量が既に入庫済の数量を下回っています。入庫済の数量以上の数値を入力するか、再表示して入庫数量を確認してください。');

/**
 * 入庫区分が"資産返却"の場合に、入力された資産管理番号が資産に存在するかどうかをチェックする
 *
 * @result {boolean} 検証結果（true: 未出庫状態（編集、取消可能）/false: 未出庫以外）
 */
$.validator.addMethod('validateAssetNo', function(value, element) {
    if (value == '') return true;

    result = WNote.ajaxValidateSend(
        '/api/asset/api-assets/validate_asset_no',
        'POST',
        {
            'asset_no': value
        }
    );

    return this.optional(element) || (result && result.validate);
}, '指定された資産管理番号が存在しません。');

/**
 * 入庫区分が"資産返却"の場合に、入力されたシリアル番号が資産に存在するかどうかをチェックする
 *
 * @result {boolean} 検証結果（true: 在庫あり/false: 在庫なし）
 */
$.validator.addMethod('validateSerialNo', function(value, element) {
    if (value == '') return true;

    result = WNote.ajaxValidateSend(
        '/api/asset/api-assets/validate_serial_no',
        'POST',
        {
            'serial_no': value
        }
    );

    return this.optional(element) || (result && result.validate);
}, '指定されたシリアル番号が存在しません。');
