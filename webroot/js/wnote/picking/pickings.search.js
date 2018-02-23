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
/** 選択中の出庫データ */
MyPage.selectData = {
    picking: {},
    cond: null
};

/** datatableのインスタンス */
MyPage.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 入庫一覧テーブル（datatable）初期化
    MyPage.datatable = $('#picking-datatable').DataTable({
        paging    : true,
        scrollY   : 540,
        scrollX   : false,
        searching : true,
        ordering  : false,
        language  : {
            search : '一覧内フィルタ　:　'
        },
        columns   : [
            { data: 'picking_kbn'         , width: '6%'  },
            { data: 'picking_date'        , width: '8%'  },
            { data: 'req_user_name'       , width: '8%'  },
            { data: 'use_user_name'       , width: '8%'  },
            { data: 'classification_name' , width: '12%' },
            { data: 'maker_name'          , width: '10%' },
            { data: 'product_name'        , width: '12%' },
            { data: 'serial_no'           , width: '8%'  },
            { data: 'asset_no'            , width: '8%'  },
            { data: 'voucher_no'          , width: '6%'  },
            { data: 'instock_suser_name'  , width: '8%'  },
            { data: 'confirm_suser_name'  , width: '8%'  }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#picking-datatable tbody').on('click', 'tr', MyPage.selectedPickingHandler);

    /** select2 登録 */
    WNote.Select2.user('#req_user_id'                , null                , true, '依頼者');
    WNote.Select2.user('#use_user_id'                , null                , true, '使用者');
    WNote.Select2.user('#dlv_user_id'                , null                , true, '出庫先');
    WNote.Select2.sUser('#picking_suser_id'          , null                , true, '出庫担当者選択');
    WNote.Select2.sUser('#confirm_suser_id'          , null                , true, '出庫確認者選択');
    WNote.Select2.classification('#classification_id', null                , true, '分類選択');
    WNote.Select2.product('#product_id'              , '#classification_id', true, '製品選択');
    WNote.Select2.model('#product_model_id'          , '#product_id'       , true, 'モデル選択');

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'search'  , MyPage.search);
    WNote.registerEvent('click', 'download', MyPage.download);

});

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫一覧取得
 *
 * @param {object} data 検索条件
 */
MyPage.getPickings = function(data) {
    // 入庫予定一覧の取得
    var result = WNote.ajaxValidateSend('/api/picking/api-pickings/search', 'POST',　{
        'cond': data.cond
    });

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、出庫の一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 出庫一覧表示
 *
 * @param {object} data 検索条件
 */
MyPage.showPickings = function(data) {
    var result = MyPage.getPickings(data);
    if (result) {
        MyPage.datatable
            .clear()
            .rows.add(result.pickings)
            .draw();
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（出庫選択）
 *  -------------------------------------------------------------------------*/
MyPage.selectedPickingHandler = function() {
}

/** ---------------------------------------------------------------------------
 *  イベント処理（編集画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 検索ボタンクリックイベントのハンドラの実装
 */
MyPage.search = function() {
    var data = WNote.Form.createAjaxData(MYPAGE.FORM_KEY);
    MyPage.selectData.cond = data.cond;
    WNote.Util.createFormElements(MYPAGE.FORM_DOWNLOAD, 'cond', data.cond); // ダウンロード用条件の保存
    WNote.Util.removeClassByDataAttr('download', 'disabled');
    MyPage.showPickings(data);
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


