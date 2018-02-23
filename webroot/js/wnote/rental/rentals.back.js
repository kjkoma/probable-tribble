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
    FORM_KEY    : 'form-rental',
    FORM_SEARCH : "form-condition"
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中の貸出データ */
MyPage.selectData = {
    cond: {}
};

/** datatableのインスタンス */
MyPage.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    /** select2 登録 */
    WNote.Select2.user('#user_id'       , null, false, '利用者選択');
    WNote.Select2.user('#admin_user_id' , null, false, '管理者選択');
    WNote.Select2.user('#back_user_id'  , null, false, '返却者選択');
    WNote.Select2.sUser('#back_suser_id', null, false, '受領者選択');

    // 貸出中一覧テーブル（datatable）初期化
    MyPage.datatable = $('#rental-datatable').DataTable({
        paging    : false,
        scrollY   : 200,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'user_name'          , width: '10%' },
            { data: 'admin_user_name'    , width: '10%' },
            { data: 'classification_name', width: '10%' },
            { data: 'maker_name'         , width: '12%' },
            { data: 'product_name'       , width: '12%' },
            { data: 'product_model_name' , width: '12%' },
            { data: 'serial_no'          , width: '8%'  },
            { data: 'asset_no'           , width: '8%'  },
            { data: 'rental_date'        , width: '8%'  },
            { data: 'rental_suser_name'  , width: '10%' }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#rental-datatable tbody').on('click', 'tr', MyPage.selectedRowHandler);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'search', MyPage.search);
    WNote.registerEvent('click', 'back'  , MyPage.backHandler);

});

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 貸出中一覧取得
 *
 * @param {object} data 検索条件
 */
MyPage.getRentals = function(data) {
    // 貸出予定一覧の取得
    var result = WNote.ajaxValidateSend('/api/rental/api-rentals/rentals', 'POST',　{
        'cond': data.cond
    });

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、貸出中の一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 貸出中一覧表示
 *
 * @param {object} data 検索条件
 */
MyPage.showRentals = function(data) {
    var result = MyPage.getRentals(data);
    MyPage.datatable
        .clear()
        .rows.add(result.rentals)
        .draw();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（検索）
 *  -------------------------------------------------------------------------*/
/**
 * 検索ボタンクリックイベントのハンドラの実装
 */
MyPage.search = function() {
    var data = WNote.Form.createAjaxData(MYPAGE.FORM_SEARCH);
    MyPage.selectData.cond = data;
    MyPage.showRentals(MyPage.selectData.cond);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（貸出中一覧行選択）
 *  -------------------------------------------------------------------------*/
MyPage.selectedRowHandler = function() {
    var selected = MyPage.datatable.row( this ).data();
    if (selected) {
        WNote.Util.All.switchHighlightDataTableRow($(this), MyPage.datatable);
    }
}

/** ---------------------------------------------------------------------------
 *  選択貸出行取得処理
 *  -------------------------------------------------------------------------*/
/**
 * 選択中の貸出中データを作成する
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
 *  イベント処理（返却）
 *  -------------------------------------------------------------------------*/
/**
 * 返却ボタンクリック時のイベントハンドラ
 *
 */
MyPage.backHandler = function() {
    WNote.showConfirmMessage('選択された貸出中の資産を在庫に更新します。返却を行ってよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.back;
}
/**
 * 返却ボタンクリックイベントのハンドラの実装
 */
MyPage.back = function() {
    // 送信データ作成
    var data = {};
    data         = WNote.Form.createAjaxData(MYPAGE.FORM_KEY);
    data.rentals = MyPage.createSaveData();

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.backValidate(data.rentals))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '返却に失敗しました。再度返却してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/rental/api-rentals/back', 'POST',
        data,
        true,
        MyPage.backSuccess
    );
}
/**
 * 返却ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.backSuccess = function(data) {
WNote.log(data); // debug
    MyPage.showRentals(MyPage.selectData.cond);
    WNote.ajaxSuccessHandler(data, '選択された資産を返却しました。');
}

/**
 * 返却時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.backValidate = function(data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // req_date
    options.rules['rental.back_date']    = { required: true, date: true, dateFormat: true };
    options.messages['rental.back_date'] = { required: '返却日を入力してください。', date: '有効な返却日をyyyy/mm/ddの形式で入力してください。', dateFormat: '返却日はyyyy/mm/ddの形式で入力してください。' };

    // req_user_id
    options.rules['rental.back_user_id']    = { required: true };
    options.messages['rental.back_user_id'] = { required: '返却者を入力してください。' };

    // remarks
    options.rules['repair.remarks']    = { maxlength: 2048 };
    options.messages['repair.remarks'] = { maxlength: '備考は最大2048文字で入力してください。' };

    // 入力内容の検証
    WNote.Form.validateClear();
    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        return WNote.Form.validateResultSet('入力内容に不備があります。各入力内容をご確認ください。');
    }

    // 貸出選択の検証
    if (!data || data.length == 0) {
        return WNote.Form.validateResultSet('返却対象が選択されていません。返却対象を選択してください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}
