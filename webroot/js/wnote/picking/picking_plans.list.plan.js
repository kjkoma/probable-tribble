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
 * 注意）このスクリプトを読み込む前に「picking_plans.list.js」を読み込むこと
 */
/** ---------------------------------------------------------------------------
 *  定数
 *  -------------------------------------------------------------------------*/
const MYPAGE_PLAN = {
    FORM_KEY: 'form-picking-plan',
    PREFIX  : 'pickingPlan_'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
MyPage.Plan = {};

/** 選択中のデータ */
MyPage.Plan.selectData = {
    detail : {},
    plan   : {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    /** Widget表示イベント追加 */
    MyPage.showPlanWidgetRegister('PlanWidget', MyPage.Plan.showWidget);
});

/** ---------------------------------------------------------------------------
 *  イベント処理（出庫予定詳細Widget表示）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定詳細入力Widget表示処理
 * 
 */
MyPage.Plan.showWidget = function() {
    WNote.Form.clearTexts(MYPAGE_PLAN.FORM_KEY);
    MyPage.Plan.getDetail(MyPage.selectData.selected.plan_detail_id)
}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定詳細データ取得
 *
 * @param {integer} detailId 出庫予定詳細ID
 */
MyPage.Plan.getDetail = function(detailId) {
    // 製品データの取得
    WNote.ajaxFailureMessage = '出庫予定詳細データの読込に失敗しました。再度出庫予定詳細を選択してください。';
    WNote.ajaxSendBasic('/api/picking/api-picking-plan-details/plan', 'POST',
        { 'detail_id': detailId },
        false,
        MyPage.Plan.getDetailSuccess
    );
}

/**
 * 出庫予定詳細取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.Plan.getDetailSuccess = function(data) {
    MyPage.Plan.selectData.detail = data.data.param.detail;
    MyPage.Plan.selectData.plan = data.data.param.detail.picking_plan;
    WNote.hideLoading();
    MyPage.Plan.setFormValues();
}

/**
 * 出庫予定詳細データをフォームに表示する
 * 
 */
MyPage.Plan.setFormValues = function() {
    WNote.Form.clearTexts(MYPAGE_PLAN.FORM_KEY);
    WNote.Form.setTextsWithPrefix(MyPage.Plan.selectData.detail, MYPAGE_PLAN.PREFIX);
    WNote.Form.setTextsWithPrefix(MyPage.Plan.selectData.plan, MYPAGE_PLAN.PREFIX);
}

