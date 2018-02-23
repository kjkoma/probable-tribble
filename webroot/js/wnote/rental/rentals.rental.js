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
    FORM_KEY: 'form-rental'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** datatableのインスタンス */
MyPage.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    /** select2 登録 */
    WNote.Select2.user('#req_user_id'  , null, false, '依頼者選択');
    WNote.Select2.user('#admin_user_id', null, false, '管理者選択');
    WNote.Select2.user('#user_id'      , null, false, '利用者選択');

    // 貸出予定一覧テーブル（datatable）初期化
    MyPage.datatable = $('#plans-datatable').DataTable({
        paging    : false,
        scrollX   : false,
        searching : true,
        ordering  : false,
        language  : {
            search : '一覧内文字列検索　:　'
        },
        columns   : [
            { data: 'asset_type_name'    , width: '10%' },
            { data: 'asset_kname'        , width: '16%' },
            { data: 'classification_name', width: '10%' },
            { data: 'maker_name'         , width: '12%' },
            { data: 'product_name'       , width: '12%' },
            { data: 'product_model_name' , width: '16%' },
            { data: 'serial_no'          , width: '12%' },
            { data: 'asset_no'           , width: '12%' }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#plans-datatable tbody').on('click', 'tr', MyPage.selectedPlanHandler);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'cancel', MyPage.cancelHandler);
    WNote.registerEvent('click', 'rental', MyPage.rentalHandler);

    /* 貸出予定一覧を表示 */
    MyPage.showPlans();
});

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 貸出予定一覧取得
 *
 */
MyPage.getPlans = function() {
    // 貸出予定一覧の取得
    var result = WNote.ajaxValidateSend('/api/rental/api-rentals/plans', 'POST',　{});

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、貸出予定の一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 貸出予定一覧表示
 *
 */
MyPage.showPlans = function() {
    var result = MyPage.getPlans();
    MyPage.datatable
        .clear()
        .rows.add(result.rentals)
        .draw();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（予定選択）
 *  -------------------------------------------------------------------------*/
MyPage.selectedPlanHandler = function() {
    var selected = MyPage.datatable.row( this ).data();
    if (selected) {
        WNote.Util.All.switchHighlightDataTableRow($(this), MyPage.datatable);
    }
}

/** ---------------------------------------------------------------------------
 *  選択予定取得処理
 *  -------------------------------------------------------------------------*/
/**
 * 選択中の貸出予定データを作成する
 *
 * @return {object} 更新データ
 */
MyPage.createSaveData = function() {
    var data = [];
    var rows = MyPage.datatable.rows('tr.selected').data();

    $(rows).each(function(i, row) {
        data.push(row);
    });

    return data;
}

/** ---------------------------------------------------------------------------
 *  イベント処理（貸出予定の削除）
 *  -------------------------------------------------------------------------*/
/**
 * 貸出予定の削除ボタンクリック時のイベントハンドラ
 *
 */
MyPage.cancelHandler = function() {
    WNote.showConfirmMessage('選択された貸出予定を削除します。予定の削除を行ってよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.cancel;
}
/**
 * 貸出予定の削除ボタンクリックイベントのハンドラの実装
 */
MyPage.cancel = function() {
    // 送信データ作成
    var data = {};
    data.plans = MyPage.createSaveData();

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.cancelValidate(data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '選択された予定の削除に失敗しました。再度削除してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/rental/api-rentals/delete', 'POST',
        data,
        true,
        MyPage.cancelSuccess
    );

}
/**
 * 貸出予定の削除ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.cancelSuccess = function(data) {
WNote.log(data); // debug
    MyPage.showPlans();
    WNote.ajaxSuccessHandler(data, '選択された予定を削除しました。');
}

/**
 * 貸出予定の削除時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.cancelValidate = function(data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // 予定選択の検証
    if (!data || data.length == 0) {
        return WNote.Form.validateResultSet('対象予定が選択されていません。貸出予定を選択してください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}


/** ---------------------------------------------------------------------------
 *  イベント処理（貸出）
 *  -------------------------------------------------------------------------*/
/**
 * 貸出ボタンクリック時のイベントハンドラ
 *
 */
MyPage.rentalHandler = function() {
    WNote.showConfirmMessage('選択された貸出予定の在庫を貸出に更新します。貸出を行ってよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.rental;
}
/**
 * 貸出ボタンクリックイベントのハンドラの実装
 */
MyPage.rental = function() {
    // 送信データ作成
    var data = {};
    data       = WNote.Form.createAjaxData(MYPAGE.FORM_KEY);
    data.plans = MyPage.createSaveData();

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.rentalValidate(data.plans))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '選択予定の貸出に失敗しました。再度貸出してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/rental/api-rentals/rental', 'POST',
        data,
        true,
        MyPage.rentalSuccess
    );
}
/**
 * 貸出ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.rentalSuccess = function(data) {
WNote.log(data); // debug
    MyPage.showPlans();
    WNote.ajaxSuccessHandler(data, '選択された予定を貸出に更新しました。');
}

/**
 * 貸出時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.rentalValidate = function(data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // req_date
    options.rules['rental.req_date']    = { required: true, date: true, dateFormat: true };
    options.messages['rental.req_date'] = { required: '依頼日を入力してください。', date: '有効な依頼日をyyyy/mm/ddの形式で入力してください。', dateFormat: '依頼日はyyyy/mm/ddの形式で入力してください。' };

    // req_user_id
    options.rules['rental.req_user_id']    = { required: true };
    options.messages['rental.req_user_id'] = { required: '依頼者を入力してください。' };

    // plan_date
    options.rules['rental.plan_date']    = { date: true, dateFormat: true };
    options.messages['rental.plan_date'] = { date: '有効な希望日をyyyy/mm/ddの形式で入力してください。', dateFormat: '希望日はyyyy/mm/ddの形式で入力してください。' };

    // user_id
    options.rules['rental.user_id']    = { required: true };
    options.messages['rental.user_id'] = { required: '利用者を入力してください。' };

    // back_plan_date
    options.rules['rental.back_plan_date']    = { date: true, dateFormat: true };
    options.messages['rental.back_plan_date'] = { date: '有効な返却予定日をyyyy/mm/ddの形式で入力してください。', dateFormat: '返却予定日はyyyy/mm/ddの形式で入力してください。' };

    // remarks
    options.rules['repair.rental_remarks']    = { maxlength: 2048 };
    options.messages['repair.rental_remarks'] = { maxlength: '貸出メモは最大2048文字で入力してください。' };

    // 入力内容の検証
    WNote.Form.validateClear();
    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        return WNote.Form.validateResultSet('入力内容に不備があります。各入力内容をご確認ください。');
    }

    // 予定選択の検証
    if (!data || data.length == 0) {
        return WNote.Form.validateResultSet('対象予定が選択されていません。貸出予定を選択してください。');
    }

    // 在庫有無の検証
    if (!MyPage.rentalTargetValidator(data)) {
        return WNote.Form.validateResultSet('選択された貸出予定の在庫がありません。該当の貸出予定を削除し、別の在庫を追加してください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}

/** ---------------------------------------------------------------------------
 *  バリデータ処理
 *  -------------------------------------------------------------------------*/
/**
 * 選択予定の在庫チェック用バリデータ
 *
 * @param {object} data 送信データ
 * @return {boolean} true: 在庫あり / false: 在庫なし
 */
MyPage.rentalTargetValidator = function(data) {

    result = WNote.ajaxValidateSend(
        '/api/rental/api-rentals/validate_rental_targets',
        'POST',
        {
            'plans': data
        }
    );

    return (result && result.validate);
}
