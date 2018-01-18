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
    FORM_KEY: ''
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中の入庫予定データ */
MyPage.selectPlan = {
    plan: {}
};

/** datatableのインスタンス */
MyPage.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 入庫予定一覧テーブル（datatable）初期化
    MyPage.datatable = $('#plans-datatable').DataTable({
        paging    : false,
        scrollX   : false,
        searching : true,
        ordering  : false,
        language  : {
            search : '一覧内文字列検索　:　'
        },
        columns   : [
            { data: 'instock_kbn_name' , width: '8%' },
            { data: 'plan_date'        , width: '10%' },
            { data: 'plan_sts_name'    , width: '8%' },
            { data: 'name'             , width: '20%' },
            { data: 'asset_no'         , width: '8%' },
            { data: 'serial_no'        , width: '8%' },
            { data: 'remarks'          , width: '38%' }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#plans-datatable tbody').on('click', 'tr', MyPage.selectedPlanHandler);

    /* 入庫予定一覧を表示 */
    MyPage.showPlans();
});

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 入庫予定一覧取得
 *
 */
MyPage.getPlans = function() {
    // 入庫予定一覧の取得
    var result = WNote.ajaxValidateSend('/api/instock/api-instock-plans/plans', 'POST',　{});

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、入庫予定の一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 入庫予定一覧表示
 *
 */
MyPage.showPlans = function() {
    var result = MyPage.getPlans();
    MyPage.datatable
        .clear()
        .rows.add(result.plans)
        .draw();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（予定選択）
 *  -------------------------------------------------------------------------*/
MyPage.selectedPlanHandler = function() {
}
