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
const WNOTE_STOCKTAKE_WORK_COUNT = {
    FORM_KEY     : 'form-elem-stocktake-work-count',
    COND_FORM_KEY: 'form-elem-stocktake-work-count-cond',
    PREFIX       : 'elemStocktakeWorkCount_',
    INPUT        : {
        COUNT : '#elemStocktakeWorkCount_stocktake_count',
        AREA  : '#elemStocktakeWorkCount_savearea'
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.StocktakeWork = WNote.StocktakeWork || {};
WNote.StocktakeWork.Count = {};

/** 選択データ */
WNote.StocktakeWork.Count.selectData = {
    cond: {},
    asset: {}
};

/** 資産一覧データテーブル */
WNote.StocktakeWork.Count.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    /** select2 登録 */
    WNote.Select2.classification('#' + WNOTE_STOCKTAKE_WORK_COUNT.PREFIX + 'classification_id' , null                , true, '分類選択');
    WNote.Select2.product('#' + WNOTE_STOCKTAKE_WORK_COUNT.PREFIX + 'product_id'               , '#' + WNOTE_STOCKTAKE_WORK_COUNT.PREFIX + 'classification_id', true, '製品選択');
    WNote.Select2.model('#' + WNOTE_STOCKTAKE_WORK_COUNT.PREFIX + 'product_model_id'           , '#' + WNOTE_STOCKTAKE_WORK_COUNT.PREFIX + 'product_model_id' , true, 'モデル選択');

    // 資産一覧テーブル（datatable）初期化
    WNote.StocktakeWork.Count.datatable = $('#elemStocktakeWorkAsset_stocktake-work-count-datatable').DataTable({
        paging    : false,
        scrollY   : 400,
        scrollX   : false,
        searching : true,
        ordering  : false,
        language  : {
            search : '一覧内フィルタ　:　'
        },
        columns   : [
            { data: 'category_name'      , width: '14%' },
            { data: 'classification_name', width: '14%' },
            { data: 'maker_name'         , width: '14%' },
            { data: 'product_name'       , width: '20%' },
            { data: 'product_model_name' , width: '20%' },
            { data: 'stock_count'        , width: '9%' },
            { data: 'stocktake_count'    , width: '9%' }
        ],
        data      : []
    });
    /** データテーブルイベント登録 */
    $('#elemStocktakeWorkAsset_stocktake-work-count-datatable tbody').on('click', 'tr', WNote.StocktakeWork.Count.selectedRow);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'stocktake-work-count-search', WNote.StocktakeWork.Count.search);
    WNote.registerEvent('click', 'stocktake-work-count-save'  , WNote.StocktakeWork.Count.save);

});

/** ---------------------------------------------------------------------------
 *  タブ選択時処理（wnote.stocktake_work.js宣言の実装）
 *  -------------------------------------------------------------------------*/
WNote.StocktakeWork.selectCountHandler = function() {
}

/** ---------------------------------------------------------------------------
 *  データ取得処理
 *  -------------------------------------------------------------------------*/
/**
 * 在庫資産一覧取得
 *
 * @param {object} data 検索条件
 */
WNote.StocktakeWork.Count.getStockCountAsset = function(data) {
    var data = WNote.ajaxValidateSend(
        '/api/stocktake/api-stocktake-targets/search_stock_count_assets',
        'POST',
        {
            'stocktake_id' : WNote.StocktakeWork.selectData.stocktakeId,
            'cond'         : data
        }
    );

    if (!data || !data.assets) {
        WNote.showErrorMessage('在庫資産情報の取得に失敗しました。再度棚卸情報を入力してください。');
    }

    return data.assets;
}

/**
 * 在庫資産一覧表示
 *
 * @param {object} data 検索条件
 */
WNote.StocktakeWork.Count.showTable = function(data) {
    var result = WNote.StocktakeWork.Count.getStockCountAsset(data);
    if (result) {
        WNote.StocktakeWork.Count.datatable
            .clear()
            .rows.add(result)
            .draw();
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（検索）
 *  -------------------------------------------------------------------------*/
/**
 * 検索ボタンクリックイベントのハンドラの実装
 */
WNote.StocktakeWork.Count.search = function() {
    var data = WNote.Form.createAjaxData(WNOTE_STOCKTAKE_WORK_COUNT.COND_FORM_KEY);
    WNote.StocktakeWork.Count.selectData.cond = data['elem-stocktake-work-count'];
    WNote.StocktakeWork.Count.showTable(WNote.StocktakeWork.Count.selectData.cond);
}

/** ---------------------------------------------------------------------------
 *  データテーブル制御処理
 *  -------------------------------------------------------------------------*/
/**
 * データテーブル行選択時のイベントハンドラー
 *
 * @param {object} event イベントオブジェクト
 */
WNote.StocktakeWork.Count.selectedRow = function(event) {
    var selected = WNote.StocktakeWork.Count.datatable.row( this ).data();
    if (selected) {
        WNote.StocktakeWork.Count.selectData.asset = selected;
        WNote.Util.All.highlightDataTableRow($(this), WNote.StocktakeWork.Count.datatable);
        $(WNOTE_STOCKTAKE_WORK_COUNT.INPUT.COUNT).val(selected.stocktake_count);
        $(WNOTE_STOCKTAKE_WORK_COUNT.INPUT.AREA).removeClass('hidden');
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリックイベントのハンドラの実装
 */
WNote.StocktakeWork.Count.save = function() {

    // 送信データ作成
    var data = WNote.Form.createAjaxData(WNOTE_STOCKTAKE_WORK_COUNT.FORM_KEY);
    data = data['elem-stocktake-work-count'];
    data.stocktake_id        = WNote.StocktakeWork.selectData.stocktakeId;
    data.stocktake_detail_id = WNote.StocktakeWork.Count.selectData.asset.stocktake_detail_id;
    data.asset_id            = WNote.StocktakeWork.Count.selectData.asset.id;
    data.classification_id   = WNote.StocktakeWork.Count.selectData.asset.classification_id;
    data.product_id          = WNote.StocktakeWork.Count.selectData.asset.product_id;
    data.product__model_id   = WNote.StocktakeWork.Count.selectData.asset.product_model_id;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, WNote.StocktakeWork.Count.saveValidate(data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '入力された内容の保存に失敗しました。再度保存してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/stocktake/api-stocktake-details/save_count', 'POST',
        { 'stocktake': data },
        true,
        WNote.StocktakeWork.Count.saveSuccess
    );

}
/**
 * 保存ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.StocktakeWork.Count.saveSuccess = function(data) {
WNote.log(data); // debug
    WNote.ajaxSuccessHandler(data, '入力された内容を保存しました。');
    WNote.StocktakeWork.Count.selectData.asset = {};
    WNote.Form.clearFormValues(WNOTE_STOCKTAKE_WORK_COUNT.FORM_KEY);
    WNote.Form.validateClear();
    $(WNOTE_STOCKTAKE_WORK_COUNT.INPUT.AREA).addClass('hidden');
    WNote.StocktakeWork.Count.showTable(WNote.StocktakeWork.Count.selectData.cond);
}

/**
 * 入庫予定データ保存時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
WNote.StocktakeWork.Count.saveValidate = function(data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // stocktake_count
    options.rules['elem-stocktake-work-count.stocktake_count']    = { required: true, digits: true, min: 0, max: 99999, maxlength: 5 };
    options.messages['elem-stocktake-work-count.stocktake_count'] = {
        required: '棚卸数を入力してください。', digits: '棚卸数は整数で入力してください。',
        min: '棚卸数は0～99999の間で入力してください。', max: '棚卸数は0～99999の間で入力してください。', maxlength: '棚卸数は0～99999の間で入力してください。' };

    // 入力内容の検証
    WNote.Form.validator = $('#' + WNOTE_STOCKTAKE_WORK_COUNT.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        return WNote.Form.validateResultSet('入力内容に不備があります。各入力内容をご確認ください。');
    }

    // 選択していないケースの検証
    if (!WNote.StocktakeWork.Count.selectData.asset.id || WNote.StocktakeWork.Count.selectData.asset.id == '') {
        return WNote.Form.validateResultSet('棚卸資産が選択されていません。棚卸資産を選択してください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}

