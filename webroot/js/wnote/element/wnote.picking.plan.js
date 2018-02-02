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
const WNOTE_PICKING_PLAN = {
    FORM_KEY : 'form-elem-picking-plan',
    PREFIX   : 'elemPickingPlan_',
    WIDGET   : '#wid-id-elem-picking-plan'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Picking = WNote.Picking || {};
WNote.Picking.Plan = {};

/** 選択データ */
WNote.Picking.Plan.selectData = {
    plan  : {},
    detal : {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {
});

/** ---------------------------------------------------------------------------
 *  取得処理
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定詳細取得
 * 
 * @param {string} detailId 出庫予定詳細ID
 * @param {object} successHandler 成功時に呼び出すハンドラー（nullの場合デフォルト使用）
 */
WNote.Picking.Plan.getPlanDetail = function(detailId, successHandler) {
    var handler = successHandler;
    if (!handler) {
        handler = WNote.Picking.Plan.getPlanDetailSuccess;
    }

    WNote.ajaxFailureMessage = '出庫データの読込に失敗しました。';
    WNote.ajaxSendBasic('/api/picking/api-picking-plan-details/plan', 'POST',
        { 'detail_id': detailId },
        true,
        handler
    );
}

/** ---------------------------------------------------------------------------
 *  表示制御
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定詳細Widgetを表示する
 *
 * @param {string} detailId 出庫予定詳細ID
 */
WNote.Picking.Plan.showWidget = function(detailId) {
    $(WNOTE_PICKING_PLAN.WIDGET).removeClass('hidden');
    WNote.Picking.Plan.getPlanDetail(detailId, null);
}

/**
 * 出庫予定詳細Widgetを非表示にする
 *
 */
WNote.Picking.Plan.hideWidget = function() {
    WNote.Form.clearTexts(WNOTE_PICKING_PLAN.FORM_KEY);
    $(WNOTE_PICKING_PLAN.WIDGET).addClass('hidden');
}

/** ---------------------------------------------------------------------------
 *  表示処理
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定詳細取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.Picking.Plan.getPlanDetailSuccess = function(data) {
    WNote.Picking.Plan.selectData.detail = data.data.param.detail;
    WNote.Picking.Plan.selectData.plan   = data.data.param.detail.picking_plan;
    WNote.hideLoading();
    WNote.Form.clearTexts(WNOTE_PICKING_PLAN.FORM_KEY);
    WNote.Form.setTextsWithPrefix(WNote.Picking.Plan.selectData.detail, WNOTE_PICKING_PLAN.PREFIX);
    WNote.Form.setTextsWithPrefix(WNote.Picking.Plan.selectData.plan  , WNOTE_PICKING_PLAN.PREFIX);
}


