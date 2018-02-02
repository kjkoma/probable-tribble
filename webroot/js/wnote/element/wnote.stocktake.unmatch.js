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
const WNOTE_STOCKTAKE_UNMATCH = {
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Stocktake = WNote.Stocktake || {};
WNote.Stocktake.Unmatch = {};

/** 資産一覧データテーブル */
WNote.Stocktake.Unmatch.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 棚卸差分テーブル（datatable）初期化
    WNote.Stocktake.Unmatch.datatable = $('#elemStocktakeUnmatch_unmatch-datatable').DataTable({
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
            { data: 'classification_name', width: '12%' },
            { data: 'product_name'       , width: '16%' },
            { data: 'product_model_name' , width: '16%' },
            { data: 'serial_no'          , width: '12%' },
            { data: 'asset_no'           , width: '12%' },
            { data: 'stocktake_count'    , width: '6%' },
            { data: 'stock_count'        , width: '6%' },
            { data: 'current_stock_count', width: '10%' }
        ],
        data      : []
    });
    /** データテーブルイベント登録 */
    $('#elemStocktakeUnmatch_unmatch-datatable tbody').on('click', 'tr', WNote.Stocktake.Unmatch.selectedRow);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'elem-stocktake-unmatch-save', WNote.Stocktake.Unmatch.save);

});

/** ---------------------------------------------------------------------------
 *  タブ選択時処理（wnote.stocktake.js宣言の実装）
 *  -------------------------------------------------------------------------*/
WNote.Stocktake.selectUnmatch = function() {
    WNote.Stocktake.Unmatch.showTable();
}

/** ---------------------------------------------------------------------------
 *  データ取得処理
 *  -------------------------------------------------------------------------*/
/**
 * 棚卸差分一覧取得
 *
 */
WNote.Stocktake.Unmatch.getList = function() {
    var data = WNote.ajaxValidateSend(
        '/api/stocktake/api-stocktake-targets/count_unmatches',
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
WNote.Stocktake.Unmatch.showTable = function() {
    var result = WNote.Stocktake.Unmatch.getList();
    if (result) {
        WNote.Stocktake.Unmatch.datatable
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
WNote.Stocktake.Unmatch.selectedRow = function(event) {
    var selected = WNote.Stocktake.Unmatch.datatable.row( this ).data();
    if (selected) {
        WNote.Util.All.switchHighlightDataTableRow($(this), WNote.Stocktake.Unmatch.datatable);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（更新）
 *  -------------------------------------------------------------------------*/
/**
 * 更新ボタンクリック時のイベントハンドラ
 *
 */
WNote.Stocktake.Unmatch.save = function() {
    WNote.showConfirmMessage('選択された棚卸差分の棚卸数で在庫を更新します。更新を行ってよろしいですか？');
    WNote.showConfirmYesHandler = WNote.Stocktake.Unmatch.saveUnmatches;
}

/**
 * 更新ボタンクリックイベントのハンドラの実装
 */
WNote.Stocktake.Unmatch.saveUnmatches = function() {

    // 送信データ作成
    var data = WNote.Stocktake.Unmatch.createSaveData();

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, WNote.Stocktake.Unmatch.saveValidate(data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '棚卸差分の更新に失敗しました。再度更新してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/stocktake/api-stocktake-details/update_count_unmatches', 'POST',
        {
            'stocktake_id': WNote.Stocktake.selectData.stocktake.id,
            'unmatches'   : data
        },
        true,
        WNote.Stocktake.Unmatch.saveSuccess
    );
}

/**
 * 棚卸差分の更新データを作成する
 *
 * @return {object} 更新データ
 */
WNote.Stocktake.Unmatch.createSaveData = function() {
    var data = [];
    var rows = WNote.Stocktake.Unmatch.datatable.rows('tr.selected').data();

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
WNote.Stocktake.Unmatch.saveSuccess = function(data) {
WNote.log(data); // debug
    WNote.ajaxSuccessHandler(data, '棚卸差分の更新に成功しました。一覧を再表示します。');
    WNote.Stocktake.Unmatch.showTable();
}

/**
 * 棚卸差分データ更新時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
WNote.Stocktake.Unmatch.saveValidate = function(data) {
    // 選択していないケースの検証
    if (!data || data.length == 0) {
        return WNote.Form.validateResultSet('棚卸差分が選択されていません。更新する棚卸差分を選択してください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}

