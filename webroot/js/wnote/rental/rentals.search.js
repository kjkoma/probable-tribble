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
    MyPage.datatable = $('#rental-datatable').DataTable({
        paging    : true,
        scrollY   : 620,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'rental_sts_name' , width: '8%'  },
            { data: 'rental_date'     , width: '8%'  },
            { data: 'user_name'       , width: '10%' },
            { data: 'admin_user_name' , width: '10%' },
            { data: 'asset_no'        , width: '12%' },
            { data: 'asset_kname'     , width: '24%' },
            { data: 'back_date'       , width: '8%'  },
            { data: 'back_user_name'  , width: '10%' },
            { data: 'back_suser_name' , width: '10%' }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#rental-datatable tbody').on('click', 'tr', MyPage.selectedRowHandler);

    /** select2 登録 */
    WNote.Select2.user('#user_id'       , null , true, '利用者選択');
    WNote.Select2.user('#admin_user_id' , null , true, '管理者選択');
    WNote.Select2.user('#back_user_id'  , null , true, '返却者選択');

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'search'  , MyPage.search);
    WNote.registerEvent('click', 'download', MyPage.download);
    WNote.registerEvent('click', 'back'    , MyPage.showSearch);
});

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 貸出一覧取得
 *
 * @param {object} data 検索条件
 */
MyPage.getRentals = function(data) {
    // 貸出一覧の取得
    var result = WNote.ajaxValidateSend('/api/rental/api-rentals/search', 'POST',　{
        'cond': data.cond
    });

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、貸出一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 貸出一覧表示
 *
 * @param {object} data 検索条件
 */
MyPage.showRentals = function(data) {
    var result = MyPage.getRentals(data);
    if (result) {
        MyPage.datatable
            .clear()
            .rows.add(result.rentals)
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
    MyPage.showRentals(data);
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
 *  イベント処理（貸出データ選択）
 *  -------------------------------------------------------------------------*/
/**
 * 貸出データ選択イベントのハンドラの実装
 */
MyPage.selectedRowHandler = function() {
    var selected = MyPage.datatable.row( this ).data();
    if (selected) {
        WNote.Util.All.highlightDataTableRow($(this), MyPage.datatable);
        $(MYPAGE.ROW.BACK).removeClass('hidden');
        WNote.Asset.showWidget(selected.asset_id);
        $(MYPAGE.ROW.SEARCH).addClass('hidden');
    }
}
