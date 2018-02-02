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
/*
 */
/** ---------------------------------------------------------------------------
 *  定数
 *  -------------------------------------------------------------------------*/
const WNOTE_STOCKTAKE_NOSTOCK = {
    FORM_KEY: 'form-elem-stocktake-nostock'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Stocktake = WNote.Stocktake || {};
WNote.Stocktake.Nostock = {};

/** 資産一覧データテーブル */
WNote.Stocktake.Nostock.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 棚卸差分テーブル（datatable）初期化
    WNote.Stocktake.Nostock.datatable = $('#elemStocktakeNostock_nostock-datatable').DataTable({
        paging    : false,
        scrollY   : 400,
        scrollX   : false,
        searching : true,
        ordering  : false,
        language  : {
            search : '一覧内フィルタ　:　'
        },
        columns   : [
            { data: 'unmatch_kbn_name'   , width: '10%' },
            { data: 'serial_no'          , width: '10%' },
            { data: 'asset_no'           , width: '10%' },
            { data: 'stocktake_kbn_name' , width: '10%' },
            { data: 'correspond'         , width: '60%' }
        ],
        data      : []
    });
    /** データテーブルイベント登録 */
    $('#elemStocktakeNostock_nostock-datatable tbody').on('click', 'tr', WNote.Stocktake.Nostock.selectedRow);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'elem-stocktake-nostock-save', WNote.Stocktake.Nostock.save);

});

/** ---------------------------------------------------------------------------
 *  タブ選択時処理（wnote.stocktake.js宣言の実装）
 *  -------------------------------------------------------------------------*/
WNote.Stocktake.selectNostockHandler = function() {
    WNote.Stocktake.Nostock.showTable();
}

/** ---------------------------------------------------------------------------
 *  データ取得処理
 *  -------------------------------------------------------------------------*/
/**
 * 棚卸差分一覧取得
 *
 */
WNote.Stocktake.Nostock.getList = function() {
    var data = WNote.ajaxValidateSend(
        '/api/stocktake/api-stocktake-details/nostocks',
        'POST',
        {
            'stocktake_id': WNote.Stocktake.selectData.stocktake.id
        }
    );

    if (!data || !data.unmatches) {
        WNote.showErrorMessage('棚卸差分データの取得に失敗しました。再度タブをクリックしてください。');
    }

    return data.unmatches;
}

/**
 * 棚卸差分一覧表示
 *
 */
WNote.Stocktake.Nostock.showTable = function() {
    var result = WNote.Stocktake.Nostock.getList();
    if (result) {
        WNote.Stocktake.Nostock.datatable
            .clear()
            .rows.add(result)
            .draw();
    }
}

/** ---------------------------------------------------------------------------
 *  データテーブル制御処理
 *  -------------------------------------------------------------------------*/
/**
 * データテーブル行選択時のイベントハンドラー
 *
 * @param {object} event イベントオブジェクト
 */
WNote.Stocktake.Nostock.selectedRow = function(event) {
    var selected = WNote.Stocktake.Nostock.datatable.row( this ).data();
    if (selected) {
        WNote.Util.All.switchHighlightDataTableRow($(this), WNote.Stocktake.Nostock.datatable);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（更新）
 *  -------------------------------------------------------------------------*/
/**
 * 更新ボタンクリック時のイベントハンドラ
 *
 */
WNote.Stocktake.Nostock.save = function() {
    WNote.showConfirmMessage('選択された棚卸差分を対応済に更新します。更新を行ってよろしいですか？');
    WNote.showConfirmYesHandler = WNote.Stocktake.Nostock.saveUnmatches;
}

/**
 * 更新ボタンクリックイベントのハンドラの実装
 */
WNote.Stocktake.Nostock.saveUnmatches = function() {

    // 送信データ作成
    var data = WNote.Stocktake.Nostock.createSaveData();

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, WNote.Stocktake.Nostock.saveValidate(data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '棚卸差分の更新に失敗しました。再度更新してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/stocktake/api-stocktake-details/update_nostocks', 'POST',
        {
            'stocktake_id': WNote.Stocktake.selectData.stocktake.id,
            'correspond'  : $('#elemStocktakeNostock_correnspond').val(),
            'unmatches'   : data
        },
        true,
        WNote.Stocktake.Nostock.saveSuccess
    );
}

/**
 * 棚卸差分の更新データを作成する
 *
 * @return {object} 更新データ
 */
WNote.Stocktake.Nostock.createSaveData = function() {
    var data = [];
    var rows = WNote.Stocktake.Nostock.datatable.rows('tr.selected').data();

    $(rows).each(function(i, row) {
        data.push(row);
    });

    return data;
}

/**
 * 更新ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.Stocktake.Nostock.saveSuccess = function(data) {
WNote.log(data); // debug
    WNote.ajaxSuccessHandler(data, '棚卸差分の更新に成功しました。一覧を再表示します。');
    WNote.Form.validateClear();
    WNote.Stocktake.Nostock.showTable();
}

/**
 * 棚卸差分データ更新時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
WNote.Stocktake.Nostock.saveValidate = function(data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // stocktake_count
    options.rules['elem-stocktake-nostock.correspond'] = { required: true, maxlength: 120 };
    options.messages['elem-stocktake-nostock.correspond'] = { required: '対応内容を入力してください。', maxlength: '対応内容は120文字以内で入力してください。' };

    // 入力内容の検証
    WNote.Form.validator = $('#' + WNOTE_STOCKTAKE_NOSTOCK.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        return WNote.Form.validateResultSet('入力内容に不備があります。各入力内容をご確認ください。');
    }

    // 選択していないケースの検証
    if (!data || data.length == 0) {
        return WNote.Form.validateResultSet('棚卸差分が選択されていません。更新する棚卸差分を選択してください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}

