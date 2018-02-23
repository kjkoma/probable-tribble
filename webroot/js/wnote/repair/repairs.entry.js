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
    FORM_KEY    : "form-repair",
    FORM_SEARCH : "form-condition"
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択データ */
MyPage.selectData = {
    asset_id: null
}

/** datatableのインスタンス */
MyPage.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 資産選択一覧テーブル（datatable）初期化
    MyPage.datatable = $('#assets-datatable').DataTable({
        paging    : false,
        scrollY   : 200,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'asset_sts_name'     , width: '8%'  },
            { data: 'asset_sub_sts_name' , width: '10%' },
            { data: 'classification_name', width: '12%' },
            { data: 'maker_name'         , width: '14%' },
            { data: 'product_name'       , width: '16%' },
            { data: 'product_model_name' , width: '16%' },
            { data: 'serial_no'          , width: '8%'  },
            { data: 'asset_no'           , width: '8%'  },
            { data: 'stock_count'        , width: '8%'  }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#assets-datatable tbody').on('click', 'tr', MyPage.selectedAssetHandler);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'save'  , MyPage.save);
    WNote.registerEvent('click', 'search', MyPage.search);
});

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 在庫選択一覧取得
 *
 * @param {object} data 検索条件
 */
MyPage.getAssets = function(data) {
    // 在庫選択一覧の取得
    var result = WNote.ajaxValidateSend('/api/stock/api-stocks/search_all', 'POST',　{
        'cond': data.cond
    });

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、在庫選択一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 在庫選択一覧表示
 *
 * @param {object} data 検索条件
 */
MyPage.showTable = function(data) {
    var result = MyPage.getAssets(data);
    if (result && result.stocks) {
        MyPage.datatable
            .clear()
            .rows.add(result.stocks)
            .draw();
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（在庫選択）
 *  -------------------------------------------------------------------------*/
MyPage.selectedAssetHandler = function() {
    var selected = MyPage.datatable.row( this ).data();
    if (selected) {
        MyPage.selectData.asset_id = selected.asset_id;
        WNote.Util.All.highlightDataTableRow($(this), MyPage.datatable);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（検索）
 *  -------------------------------------------------------------------------*/
/**
 * 検索ボタンクリックイベントのハンドラの実装
 */
MyPage.search = function() {
    var data = WNote.Form.createAjaxData(MYPAGE.FORM_SEARCH);

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.searchValidate(data))) {
        return;
    }

    MyPage.selectData.asset_id = null;
    WNote.Form.validateClear();
    MyPage.showTable(data);
}

/**
 * 検索時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.searchValidate = function(data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // serial_no
    options.rules['cond.serial_no']    = { required: MyPage.validateSerialAndAssetNo, maxlength: 120 };
    options.messages['cond.serial_no'] = { required: 'シリアル番号か資産管理番号のいずれかをを入力してください。', maxlength: 'シリアル番号は最大120文字で入力してください。' };

    // asset_no
    options.rules['cond.asset_no']     = { required: MyPage.validateSerialAndAssetNo, maxlength: 60 };
    options.messages['cond.asset_no']  = { required: 'シリアル番号か資産管理番号のいずれかをを入力してください。', maxlength: '資産管理番号は最大60文字で入力してください。' };

    // 入力内容の検証
    WNote.Form.validateClear();
    WNote.Form.validator = $('#' + MYPAGE.FORM_SEARCH).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        return WNote.Form.validateResultSet('入力内容に不備があります。各入力内容をご確認ください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}

/**
 * 資産管理番号とシリアル番号の相関チェック
 *
 * @result {boolean} 検証結果（true: 入力あり/false: 入力なし）
 */
MyPage.validateSerialAndAssetNo = function(element) {
    var prefix = $(element).attr('name').split('.')[0];
    var assetNo  = $.trim($('input[name="'+ prefix +'.asset_no"]').val());
    var serialNo = $.trim($('input[name="'+ prefix +'.serial_no"]').val());

    // いずれかの入力が必須
    if (assetNo == '' && serialNo == '') return true;

    return false;
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリックイベントのハンドラの実装
 */
MyPage.save = function() {
    // 送信データ作成
    var data = WNote.Form.createAjaxData(MYPAGE.FORM_KEY);
    data.asset_id = MyPage.selectData.asset_id;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.saveValidate(data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '入力された内容の保存に失敗しました。再度保存してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/repair/api-repairs/entry', 'POST',
        data,
        true,
        MyPage.saveSuccess
    );

}
/**
 * 保存ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.saveSuccess = function(data) {
WNote.log(data); // debug
    MyPage.selectData.asset_id = null;
    WNote.Form.validateClear();
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.clearFormValues(MYPAGE.FORM_SEARCH);
    MyPage.datatable.clear().draw();
    WNote.ajaxSuccessHandler(data, '入力された内容を登録しました。');
}

/**
 * 修理データ保存時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.saveValidate = function(data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // start_date
    options.rules['repair.start_date']    = { required: true, date: true, dateFormat: true };
    options.messages['repair.start_date'] = { required: '発生日を入力してください。', date: '有効な発生日をyyyy/mm/ddの形式で入力してください。', dateFormat: '発生日はyyyy/mm/ddの形式で入力してください。' };

    // trouble_kbn
    options.rules['repair.trouble_kbn']    = { required: true };
    options.messages['repair.trouble_kbn'] = { required: '故障区分を選択してください。' };

    // trouble_reason
    options.rules['repair.trouble_reason']    = { maxlength: 2048 };
    options.messages['repair.trouble_reason'] = { maxlength: '故障原因は最大2048文字で入力してください。' };

    // datapick_kbn
    options.rules['repair.datapick_kbn']    = { required: true };
    options.messages['repair.datapick_kbn'] = { required: 'データ抽出区分を選択してください。' };

    // sendback_kbn
    options.rules['repair.sendback_kbn']    = { required: true };
    options.messages['repair.sendback_kbn'] = { required: 'センドバック区分を選択してください。' };

    // remarks
    options.rules['repair.remarks']    = { maxlength: 2048 };
    options.messages['repair.remarks'] = { maxlength: '補足は最大2048文字で入力してください。' };

    // 入力内容の検証
    WNote.Form.validateClear();
    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        return WNote.Form.validateResultSet('入力内容に不備があります。各入力内容をご確認ください。');
    }

    // 在庫選択の検証
    if (!data.asset_id || data.asset_id == '') {
        return WNote.Form.validateResultSet('対象在庫が選択されていません。修理対象在庫を選択してください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}
