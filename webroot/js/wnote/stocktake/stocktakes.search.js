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
    FORM_KEY     : 'form-condition',
    FORM_DOWNLOAD: 'form-download',
    ROW : {
        SEARCH: '#grid-row-search',
        BACK  : '#grid-row-back'
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 検索条件 */
MyPage.selectData = {
    cond: null
}

/** datatableのインスタンス */
MyPage.datatable = null;


/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 資産一覧テーブル（datatable）初期化
    MyPage.datatable = $('#stocktake-datatable').DataTable({
        paging    : true,
        scrollY   : 420,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'stocktake_sts_name'  , width: '8%' },
            { data: 'stocktake_date'      , width: '8%' },
            { data: 'stocktake_suser_name', width: '8%' },
            { data: 'confirm_suser_name'  , width: '8%' },
            { data: 'start_date'          , width: '8%' },
            { data: 'end_date'            , width: '8%' },
            { data: 'stock_deadline_date' , width: '8%' },
            { data: 'remarks'             , width: '44%'  }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#stocktake-datatable tbody').on('click', 'tr', MyPage.selectedStocktakeHandler);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'search'  , MyPage.search);
    WNote.registerEvent('click', 'download', MyPage.download);
    WNote.registerEvent('click', 'back'    , MyPage.showSearch);
});

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 棚卸一覧取得
 *
 * @param {object} data 検索条件
 */
MyPage.getStocktakes = function(data) {
    // 棚卸一覧の取得
    var result = WNote.ajaxValidateSend('/api/stocktake/api-stocktakes/search', 'POST',　{
        'cond': data.cond
    });

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、棚卸一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 棚卸一覧表示
 *
 * @param {object} data 検索条件
 */
MyPage.showStocktakes = function(data) {
    var result = MyPage.getStocktakes(data);
    if (result) {
        MyPage.datatable
            .clear()
            .rows.add(result.stocktakes)
            .draw();
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（検索）
 *  -------------------------------------------------------------------------*/
/**
 * 検索ボタンクリックイベントのハンドラの実装
 */
MyPage.search = function() {
    var data = WNote.Form.createAjaxData(MYPAGE.FORM_KEY);
    MyPage.selectData.cond = data.cond;
    WNote.Util.createFormElements(MYPAGE.FORM_DOWNLOAD, 'cond', data.cond); // ダウンロード用条件の保存
    MyPage.showStocktakes(data);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（ダウンロード）
 *  -------------------------------------------------------------------------*/
/**
 * ダウンロードボタンクリックイベントのハンドラの実装
 */
MyPage.download = function() {
    $('#' + MYPAGE.FORM_DOWNLOAD).submit();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（検索表示）
 *  -------------------------------------------------------------------------*/
/**
 * 検索ボタンクリックイベントのハンドラの実装
 */
MyPage.showSearch = function() {
    $(MYPAGE.ROW.SEARCH).removeClass('hidden');
    WNote.Stocktake.hideWidget();
    $(MYPAGE.ROW.BACK).addClass('hidden');
}

/** ---------------------------------------------------------------------------
 *  イベント処理（棚卸選択）
 *  -------------------------------------------------------------------------*/
/**
 * 棚卸選択イベントのハンドラの実装
 */
MyPage.selectedStocktakeHandler = function() {
    var selected = MyPage.datatable.row( this ).data();
    if (selected) {
        WNote.Util.All.highlightDataTableRow($(this), MyPage.datatable);
        $(MYPAGE.ROW.BACK).removeClass('hidden');
        $(MYPAGE.ROW.SEARCH).addClass('hidden');
        WNote.Stocktake.showWidget(selected.id);
    }
}
