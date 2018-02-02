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
const WNOTE_STOCKTAKE_WORK_ASSET = {
    FORM_KEY : 'form-elem-stocktake-work-asset',
    PREFIX   : 'elemStocktakeWorkAsset_',
    INPUT    : {
        ASSET : '#elemStocktakeWorkAsset_asset_input',
        SERIAL: '#elemStocktakeWorkAsset_serial_input'
    },
    TEXT     : {
        STOCK   : '#elemStocktakeWorkAsset_stocktake_stock_count',
        NOSTOCK : '#elemStocktakeWorkAsset_stocktake_nostock_count'
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.StocktakeWork = WNote.StocktakeWork || {};
WNote.StocktakeWork.Asset = {};

/** 棚卸入力一覧データテーブル */
WNote.StocktakeWork.Asset.datatable = null;

/** 棚卸入力一覧件数 */
WNote.StocktakeWork.Asset.count = {
    stock   : 0,
    nostock : 0
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 棚卸入力一覧テーブル（datatable）初期化
    WNote.StocktakeWork.Asset.datatable = $('#elemStocktakeWorkAsset_stocktake-work-asset-datatable').DataTable({
        paging    : false,
        scrollY   : 400,
        scrollX   : false,
        searching : true,
        ordering  : false,
        language  : {
            search : '一覧内フィルタ　:　'
        },
        columns   : [
            { data: 'exist_stock'        , width: '10%' },
            { data: 'asset_no'           , width: '12%' },
            { data: 'serial_no'          , width: '12%' },
            { data: 'classification_name', width: '14%' },
            { data: 'product_name'       , width: '24%' },
            { data: 'product_model_name' , width: '24%' }
        ],
        data      : []
    });
    /** データテーブルイベント登録 */
    $('#elemStocktakeWorkAsset_stocktake-work-asset-datatable tbody').on('click', 'tr', WNote.StocktakeWork.Asset.selectedRow);

    /** 入力テキストのEnterキー押下イベント／変更イベント登録 */
    $(WNOTE_STOCKTAKE_WORK_ASSET.INPUT.ASSET ).on('keypress', function (e) { if(e.which === 13){ WNote.StocktakeWork.Asset.inputAsset(e); }});
    $(WNOTE_STOCKTAKE_WORK_ASSET.INPUT.SERIAL).on('keypress', function (e) { if(e.which === 13){ WNote.StocktakeWork.Asset.inputSerial(e); }});

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'stocktake-work-asset-clear'     , WNote.StocktakeWork.Asset.clearSelectedRows);
    WNote.registerEvent('click', 'stocktake-work-asset-delete'    , WNote.StocktakeWork.Asset.removeSelectedDtRows);
    WNote.registerEvent('click', 'stocktake-work-asset-delete-all', WNote.StocktakeWork.Asset.removeAllDtRows);
    WNote.registerEvent('click', 'stocktake-work-asset-save'      , WNote.StocktakeWork.Asset.save);

});

/** ---------------------------------------------------------------------------
 *  タブ選択時処理（wnote.stocktake_work.js宣言の実装）
 *  -------------------------------------------------------------------------*/
WNote.StocktakeWork.selectAssetHandler = function() {
}

/** ---------------------------------------------------------------------------
 *  データ取得処理
 *  -------------------------------------------------------------------------*/
/**
 * 在庫資産の情報を取得する
 *
 * @param {string} serial_no シリアル番号
 * @param {string} asset_no 資産管理番号
 */
WNote.StocktakeWork.Asset.getStockAsset = function(serial_no, asset_no) {
    var data = WNote.ajaxValidateSend(
        '/api/stocktake/api-stocktake-targets/stock_asset_by_serial_or_asset_no',
        'POST',
        {
            'asset_no' : asset_no,
            'serial_no': serial_no
        }
    );

    if (!data || !data.asset) {
        WNote.showErrorMessage('在庫資産情報の取得に失敗しました。再度棚卸情報を入力してください。');
    }

    return data.asset;
}


/** ---------------------------------------------------------------------------
 *  フォーム入力・表示制御
 *  -------------------------------------------------------------------------*/
/**
 * フォームをクリアする
 *
 */
WNote.StocktakeWork.Asset.clear = function() {
    $(WNOTE_STOCKTAKE_WORK_ASSET.INPUT.ASSET).val('');
    $(WNOTE_STOCKTAKE_WORK_ASSET.INPUT.SERIAL).val('');
    WNote.StocktakeWork.Asset.removeAllDtRows();
}

/**
 * 入力件数・未在庫件数を再計算・表示する
 *
 */
WNote.StocktakeWork.Asset.reCount = function() {
    WNote.StocktakeWork.Asset.count.stock   = 0;
    WNote.StocktakeWork.Asset.count.nostock = 0;

    var datas = WNote.StocktakeWork.Asset.datatable.rows().data();
    $(datas).each(function( i, data ) {
        WNote.StocktakeWork.Asset.addCount(data);
    });
}

/**
 * 入力件数・未在庫件数を加算・表示する
 *
 * @param {object} data 行データ
 */
WNote.StocktakeWork.Asset.addCount = function(data) {
    if (data.exist_stock != '') {
        WNote.StocktakeWork.Asset.count.nostock++;
        $(WNOTE_STOCKTAKE_WORK_ASSET.TEXT.NOSTOCK).text(WNote.StocktakeWork.Asset.count.nostock);
    } else {
        WNote.StocktakeWork.Asset.count.stock++;
        $(WNOTE_STOCKTAKE_WORK_ASSET.TEXT.STOCK).text(WNote.StocktakeWork.Asset.count.stock);
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
WNote.StocktakeWork.Asset.selectedRow = function(event) {
    var selected = WNote.StocktakeWork.Asset.datatable.row( this ).data();
    if (selected) {
        WNote.Util.All.switchHighlightDataTableRow($(this), WNote.StocktakeWork.Asset.datatable);
    }
}

/**
 * データテーブル行選択を全解除する
 *
 * @param {object} event イベントオブジェクト
 */
WNote.StocktakeWork.Asset.clearSelectedRows = function(event) {
    WNote.StocktakeWork.Asset.datatable.$('tr.selected').removeClass('selected');
}

/**
 * 選択された行を削除する
 *
 */
WNote.StocktakeWork.Asset.removeSelectedDtRows = function() {
    WNote.StocktakeWork.Asset.datatable.rows('tr.selected').remove().draw();
    WNote.StocktakeWork.Asset.reCount();
}

/**
 * 全行を削除する
 *
 */
WNote.StocktakeWork.Asset.removeAllDtRows = function() {
    WNote.StocktakeWork.Asset.datatable.clear().draw();
    WNote.StocktakeWork.Asset.reCount();
}

/**
 * 指定されたデータの行を追加する
 *
 * @param {object} data 追加するデータ
 */
WNote.StocktakeWork.Asset.addDtRow = function(data) {
    WNote.StocktakeWork.Asset.datatable.row.add(data).draw();
    WNote.StocktakeWork.Asset.addCount(data);
}

/**
 * 行追加のブランクデータを作成する
 *
 * @return {object} 行追加のブランクデータ
 */
WNote.StocktakeWork.Asset.createBlankRow = function() {
    var data = {};
    data.exist_stock         = '在庫なし';
    data.asset_no            = '';
    data.serial_no           = '';
    data.classification_name = '';
    data.product_name        = '';
    data.product_model_name  = '';

    return data;
}

/** ---------------------------------------------------------------------------
 *  イベント処理（入力 + Enter）
 *  -------------------------------------------------------------------------*/
/**
 * 資産管理番号入力後のEnterキー押下時の処理
 *
 * @param {object} event イベントオブジェクト
 */
WNote.StocktakeWork.Asset.inputAsset = function(event) {
    var value = $(event.target).val().trim();
    if (value == '') return;

    if (!WNote.StocktakeWork.Asset.validateInput('', value)) {
        return;
    }

    var asset = WNote.StocktakeWork.Asset.getStockAsset('', value);
    asset.exist_stock = '';
    if (!asset.id) {
        asset = WNote.StocktakeWork.Asset.createBlankRow();
        asset.asset_no = value;
    }
    WNote.StocktakeWork.Asset.addDtRow(asset);
    $(WNOTE_STOCKTAKE_WORK_ASSET.INPUT.ASSET).val('');
}

/**
 * シリアル番号入力後のEnterキー押下時の処理
 *
 * @param {object} event イベントオブジェクト
 */
WNote.StocktakeWork.Asset.inputSerial = function(event) {
    var value = $(event.target).val().trim();
    if (value == '') return;

    if (!WNote.StocktakeWork.Asset.validateInput(value, '')) {
        return;
    }

    var asset = WNote.StocktakeWork.Asset.getStockAsset(value, '');
    asset.exist_stock = '';
    if (!asset.id) {
        asset = WNote.StocktakeWork.Asset.createBlankRow();
        asset.serial_no = value;
    }
    WNote.StocktakeWork.Asset.addDtRow(asset);
    $(WNOTE_STOCKTAKE_WORK_ASSET.INPUT.SERIAL).val('');
}

/**
 * 入力シリアルと資産管理番号の重複チェック
 *
 * @param {string} serial シリアル番号
 * @param {string} asset  資産管理番号
 * @return {boolean} true: 重複なし / false: 重複あり
 */
WNote.StocktakeWork.Asset.validateInput = function(serial, asset) {
    var validate = true;
    var rows     = WNote.StocktakeWork.Asset.datatable.rows().data();

    $(rows).each(function(i, row) {
        if (row.serial_no == serial && row.asset_no == asset) {
            WNote.showWarningMessage('入力されたシリアル、または、資産管理番号はすでに入力済です。');
            validate = false;
            return false;
        }
    });

    return validate;
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリック時のイベントハンドラー
 *
 */
WNote.StocktakeWork.Asset.save = function() {
    WNote.showConfirmMessage('入力された棚卸の内容を保存します。保存を行ってよろしいですか？');
    WNote.showConfirmYesHandler = WNote.StocktakeWork.Asset.saveStocktake;
}

/**
 * 棚卸一覧の入力内容を保存する
 *
 */
WNote.StocktakeWork.Asset.saveStocktake = function() {
    // 送信データ作成
    var data = WNote.StocktakeWork.Asset.createSaveData();

    // 送信データの検証
    if (WNote.StocktakeWork.Asset.datatable.column(0).data().length == 0) {
        WNote.showWarningMessage('棚卸内容がありません。棚卸内容を入力してください。');
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '棚卸内容の保存に失敗しました。再度保存してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/stocktake/api-stocktake-details/save', 'POST',
        {
            'stocktake_id' : WNote.StocktakeWork.selectData.stocktakeId,
            'stocktakes'   : data
        },
        true,
        WNote.StocktakeWork.Asset.saveSuccess
    );
}

/**
 * 棚卸一覧の保存データを作成する
 *
 * @return {object} 保存データ
 */
WNote.StocktakeWork.Asset.createSaveData = function() {
    var data = [];
    var rows = WNote.StocktakeWork.Asset.datatable.rows().data();

    $(rows).each(function(i, row) {
        data.push(row);
    });

    return data;
}

/**
 * 保存ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.StocktakeWork.Asset.saveSuccess = function(data) {
WNote.log(data); // debug
    WNote.ajaxSuccessHandler(data, '棚卸内容が正常に保存されました。');
    WNote.StocktakeWork.Asset.clear();
}
