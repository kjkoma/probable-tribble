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
    MyPage.datatable = $('#stock-datatable').DataTable({
        paging    : true,
        scrollY   : 620,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'asset_type_name'     , width: '7%'  },
            { data: 'asset_sts_name'      , width: '6%' },
            { data: 'asset_sub_sts_name'  , width: '7%' },
            { data: 'kname'               , width: '16%' },
            { data: 'classification_name' , width: '12%' },
            { data: 'maker_name'          , width: '8%' },
            { data: 'product_name'        , width: '18%' },
            { data: 'serial_no'           , width: '10%'  },
            { data: 'asset_no'            , width: '8%'  },
            { data: 'stock_count'         , width: '8%'  }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#stock-datatable tbody').on('click', 'tr', MyPage.selectedAssetHandler);

    /** select2 登録 */
    WNote.Select2.classification('#classification_id'     , '#category_id'      , true, '分類選択');
    WNote.Select2.product('#product_id'                   , '#classification_id', true, '製品選択');
    WNote.Select2.model('#product_model_id'               , '#product_model_id' , true, 'モデル選択');

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'search'  , MyPage.search);
    WNote.registerEvent('click', 'download', MyPage.download);
    WNote.registerEvent('click', 'back'    , MyPage.showSearch);
});

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 在庫一覧取得
 *
 * @param {object} data 検索条件
 */
MyPage.getStocks = function(data) {
    // 在庫一覧の取得
    var result = WNote.ajaxValidateSend('/api/stock/api-stocks/search', 'POST',　{
        'cond': data.cond
    });

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、在庫一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 在庫一覧表示
 *
 * @param {object} data 検索条件
 */
MyPage.showStocks = function(data) {
    var result = MyPage.getStocks(data);
    if (result) {
        MyPage.datatable
            .clear()
            .rows.add(result.stocks)
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
    MyPage.showStocks(data);
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
 * 検索表示ボタンクリックイベントのハンドラの実装
 */
MyPage.showSearch = function() {
    $(MYPAGE.ROW.SEARCH).removeClass('hidden');
    WNote.Asset.hideWidget();
    $(MYPAGE.ROW.BACK).addClass('hidden');
}

/** ---------------------------------------------------------------------------
 *  イベント処理（資産選択）
 *  -------------------------------------------------------------------------*/
/**
 * 資産選択イベントのハンドラの実装
 */
MyPage.selectedAssetHandler = function() {
    var selected = MyPage.datatable.row( this ).data();
    if (selected) {
        WNote.Util.All.highlightDataTableRow($(this), MyPage.datatable);
        $(MYPAGE.ROW.BACK).removeClass('hidden');
        WNote.Asset.showWidget(selected.asset_id);
        $(MYPAGE.ROW.SEARCH).addClass('hidden');
    }
}
