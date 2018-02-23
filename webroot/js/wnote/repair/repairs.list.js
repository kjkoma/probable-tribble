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
        LIST : '#grid-row-list',
        BACK : '#grid-row-back'
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
    $('#repair-datatable tbody').on('click', 'tr', MyPage.selectedRowHandler);

    /** select2 登録 */
    WNote.Select2.user('#req_user_id'                , null                , true, '依頼者選択');
    WNote.Select2.classification('#classification_id', null                , true, '分類選択');
    WNote.Select2.product('#product_id'              , '#classification_id', true, '製品選択');
    WNote.Select2.model('#product_model_id'          , '#product_model_id' , true, 'モデル選択');

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'search'  , MyPage.search);
    WNote.registerEvent('click', 'download', MyPage.download);
    WNote.registerEvent('click', 'back'    , MyPage.showList);

    /** 修理の完了・廃棄イベント登録 */
    WNote.RepairsHistories.afterCompleteOrAbrogateRegister('repair', MyPage.refreshList);

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
        'cond': data
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
MyPage.showTable = function(data) {
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
    WNote.Util.removeClassByDataAttr('download', 'disabled');
    MyPage.showTable(data.cond);
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
 *  イベント処理（一覧表示）
 *  -------------------------------------------------------------------------*/
/**
 * 一覧へ戻るボタンクリックイベントのハンドラの実装
 */
MyPage.showList = function() {
    $(MYPAGE.ROW.LIST).removeClass('hidden');
    WNote.RepairsHistories.hideWidget();
    $(MYPAGE.ROW.BACK).addClass('hidden');
}

/**
 * 修理完了 or 廃棄時の再表示処理
 */
MyPage.refreshList = function() {
    MyPage.showTable(MyPage.selectData.cond);
    MyPage.showList();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（修理選択）
 *  -------------------------------------------------------------------------*/
/**
 * 修理選択イベントのハンドラの実装
 */
MyPage.selectedRowHandler = function() {
    var selected = MyPage.datatable.row( this ).data();
    if (selected) {
        WNote.Util.All.highlightDataTableRow($(this), MyPage.datatable);
        $(MYPAGE.ROW.BACK).removeClass('hidden');
        WNote.RepairsHistories.showWidget(selected.id, selected.repair_sts);
        $(MYPAGE.ROW.LIST).addClass('hidden');
    }
}

