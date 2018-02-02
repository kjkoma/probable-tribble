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
    FORM_DOWNLOAD: 'form-download'
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

    // 修理一覧テーブル（datatable）初期化
    MyPage.datatable = $('#repair-datatable').DataTable({
        paging    : false,
        scrollY   : 620,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'repair_sts_name'     , width: '8%'  },
            { data: 'req_date'            , width: '8%'  },
            { data: 'req_user_name'       , width: '10%' },
            { data: 'category_name'       , width: '10%' },
            { data: 'classification_name' , width: '12%' },
            { data: 'maker_name'          , width: '12%' },
            { data: 'product_name'        , width: '14%' },
            { data: 'asset_no'            , width: '8%'  },
            { data: 'serial_no'           , width: '8%'  },
            { data: 'trouble_kbn_name'    , width: '8%'  }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    // $('#stock-datatable tbody').on('click', 'tr', MyPage.selectedStockHandler);

    /** select2 登録 */
    WNote.Select2.user('#req_user_id'                , null                , true, '依頼者選択');
    WNote.Select2.classification('#classification_id', null                , true, '分類選択');
    WNote.Select2.product('#product_id'              , '#classification_id', true, '製品選択');
    WNote.Select2.model('#product_model_id'          , '#product_model_id' , true, 'モデル選択');

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'search', MyPage.search);
    WNote.registerEvent('click', 'download', MyPage.download);

    // 初回画面表示
    MyPage.search();

});

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 修理一覧取得
 *
 * @param {object} data 検索条件
 */
MyPage.getList = function(data) {
    // 修理一覧の取得
    var result = WNote.ajaxValidateSend('/api/repair/api-repairs/search', 'POST',　{
        'cond': data.cond
    });

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、修理一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 修理一覧表示
 *
 * @param {object} data 検索条件
 */
MyPage.showList = function(data) {
    var result = MyPage.getList(data);
    if (result) {
        MyPage.datatable
            .clear()
            .rows.add(result.repairs)
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
    MyPage.showList(data);
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
