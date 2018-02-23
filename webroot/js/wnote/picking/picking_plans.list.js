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
    FORM_KEY: 'form-condition',
    FORM_DOWNLOAD: 'form-download',
    WIDGET  : {
        ENTRY   : '#wid-id-entry',
        CANCEL  : '#wid-id-cancel',
        PLAN    : '#wid-id-plan',
        ASSET   : '#art-asset'
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中のデータ */
MyPage.selectData = {
    condition : {},
    selected  : {}
};

/** 出庫予定一覧データテーブル */
MyPage.datatable = null;

/** 出庫予定一覧データテーブル選択行 */
MyPage.selectedRowTarget = null; // 要素
MyPage.selectedRow = null;       // datatable row-selector

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 出庫予定一覧テーブル（datatable）初期化(picking_plans.list.jsのオブジェクトに設定)
    MyPage.datatable = $('#plans-datatable').DataTable({
        paging    : false,
        scrollY   : 400,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'plan_kbn_name'        , width: '5%'  },
            { data: 'plan_sts_name'        , width: '7%'  },
            { data: 'plan_date'            , width: '7%'  },
            { data: 'req_date'             , width: '7%'  },
            { data: 'req_user_name'        , width: '7%' },
            { data: 'arv_date'             , width: '7%'  },
            { data: 'rcv_suser_name'       , width: '7%' },
            { data: 'apply_no'             , width: '10%'  },
            { data: 'work_suser_name'      , width: '7%' },
            { data: 'category_name'        , width: '12%' },
            { data: 'kitting_pattern_name' , width: '14%' },
            { data: 'serial_no'            , width: '10%'  }
        ],
        data      : []
    });

    /** データテーブルイベント登録(picking_plans.list.js内のイベントを呼び出す) */
    $('#plans-datatable tbody').on('click', 'tr', MyPage.selectedPlansHandler);

    /** select2 登録 */
    WNote.Select2.user('#cond_req_user_id'   , null, true, '依頼者');
    WNote.Select2.user('#cond_use_user_id'   , null, true, '使用者');
    WNote.Select2.sUser('#cond_work_suser_id', null, true, '出庫作業者');
    WNote.Select2.sUser('#cond_rcv_suser_id' , null, true, '受付者');

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'search'  , MyPage.search);
    WNote.registerEvent('click', 'download', MyPage.download);

    /** 一覧を初期表示 */
    MyPage.search();
});

/** ---------------------------------------------------------------------------
 *  イベント処理（一覧選択）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定選択イベントハンドラー
 * 
 * @param {object} event イベント
 */
MyPage.selectedPlansHandler = function(event) {
    MyPage.selectedRowTarget = $(event.target);
    MyPage.selectedRow = this;
    var selected = MyPage.datatable.row( this ).data();
    if (selected) {
        MyPage.selectData.selected = selected;
        WNote.Util.All.highlightDataTableRow($(this), MyPage.datatable);
    }

    if (selected.asset_id && selected.asset_id != '') {
        // Entry Widget & Asset Widget 表示
        MyPage.selctedPlanHaveAsset();
    } else {
        if (selected.plan_sts == $('#plan_sts_cancel').val()) {
            // Cancel Widget表示
            MyPage.selctedPlanCancel();
        } else {
            // Entry Widget表示
            MyPage.selctedPlanNonAsset();
        }
    }
}

/**
 * 出庫予定選択 - 出庫資産割り当てあり時の処理
 * 
 */
MyPage.selctedPlanHaveAsset = function() {
    MyPage.hideCancelWidget();
    MyPage.showPlanWidget();
    MyPage.showEntryWidget();
    MyPage.showAssetWidget();
}

/**
 * 出庫予定選択 - 出庫資産割り当てなし時(取消確認時以外)の処理
 * 
 */
MyPage.selctedPlanNonAsset = function() {
    MyPage.hideCancelWidget();
    MyPage.hideAssetWidget();
    MyPage.showPlanWidget();
    MyPage.showEntryWidget();
}

/**
 * 出庫予定選択 - 出庫資産割り当てなし時(取消確認時)の処理
 * 
 */
MyPage.selctedPlanCancel = function() {
    MyPage.hideEntryWidget();
    MyPage.hideAssetWidget();
    MyPage.showPlanWidget();
    MyPage.showCancelWidget();
}

/**
 * 出庫予定再選択（詳細データ追加） - picking_plans.list.entry.jsで呼び出す
 * 
 * @param {object} plan 行更新データ
 */
MyPage.reSelectedRow = function(plan) {
    MyPage.datatable.row(MyPage.selectedRow).data(plan).draw();
    MyPage.selectedRowTarget.click();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（Widget表示/非表示）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫詳細入力Widget表示
 * 
 */
MyPage.showEntryWidget = function() {
    $(MYPAGE.WIDGET.ENTRY).removeClass('hidden');
    $.each(MyPage.showEntryWidgetEvents, function(key, fn) { fn(); });
}
/** 出庫詳細入力Widget表示イベント（利用側で関数を登録） */
MyPage.showEntryWidgetEvents = {};
MyPage.showEntryWidgetRegister = function(handleName, handler) {
    MyPage.showEntryWidgetEvents[handleName] = handler;
}

/**
 * 出庫詳細入力Widget非表示
 * 
 */
MyPage.hideEntryWidget = function() {
    $(MYPAGE.WIDGET.ENTRY).addClass('hidden');
    $.each(MyPage.hideEntryWidgetEvents, function(key, fn) { fn(); });
}
/** 出庫詳細入力Widget非表示イベント（利用側で関数を登録） */
MyPage.hideEntryWidgetEvents = {};
MyPage.hideEntryWidgetRegister = function(handleName, handler) {
    MyPage.hideEntryWidgetEvents[handleName] = handler;
}

/**
 * 取消確認Widget表示
 * 
 */
MyPage.showCancelWidget = function() {
    $(MYPAGE.WIDGET.CANCEL).removeClass('hidden');
    $.each(MyPage.showCancelWidgetEvents, function(key, fn) { fn(); });
}
/** 出庫詳細入力Widget表示イベント（利用側で関数を登録） */
MyPage.showCancelWidgetEvents = {};
MyPage.showCancelWidgetRegister = function(handleName, handler) {
    MyPage.showCancelWidgetEvents[handleName] = handler;
}

/**
 * 取消確認Widget非表示
 * 
 */
MyPage.hideCancelWidget = function() {
    $(MYPAGE.WIDGET.CANCEL).addClass('hidden');
    $.each(MyPage.hideCancelWidgetEvents, function(key, fn) { fn(); });
}
/** 出庫詳細入力Widget非表示イベント（利用側で関数を登録） */
MyPage.hideCancelWidgetEvents = {};
MyPage.hideCancelWidgetRegister = function(handleName, handler) {
    MyPage.hideCancelWidgetEvents[handleName] = handler;
}

/**
 * 出庫内容詳細Widget表示
 * 
 */
MyPage.showPlanWidget = function() {
    $(MYPAGE.WIDGET.PLAN).removeClass('hidden');
    $.each(MyPage.showPlanWidgetEvents, function(key, fn) { fn(); });
}
/** 出庫詳細入力Widget表示イベント（利用側で関数を登録） */
MyPage.showPlanWidgetEvents = {};
MyPage.showPlanWidgetRegister = function(handleName, handler) {
    MyPage.showPlanWidgetEvents[handleName] = handler;
}

/**
 * 出庫内容詳細Widget非表示
 * 
 */
MyPage.hidePlanWidget = function() {
    $(MYPAGE.WIDGET.PLAN).addClass('hidden');
    $.each(MyPage.hidePlanWidgetEvents, function(key, fn) { fn(); });
}
/** 出庫詳細入力Widget非表示イベントハンドラー（利用側で関数を登録） */
MyPage.hidePlanWidgetEvents = {};
MyPage.hidePlanWidgetRegister = function(handleName, handler) {
    MyPage.hidePlanWidgetEvents[handleName] = handler;
}

/**
 * 資産Widget表示
 * 
 */
MyPage.showAssetWidget = function() {
    if (MyPage.selectData.selected.asset_id) {
        WNote.Asset.showWidget(MyPage.selectData.selected.asset_id);
        $.each(MyPage.showAssetWidgetEvents, function(key, fn) { fn(); });
    }
}
/** 資産Widget表示イベント（利用側で関数を登録） */
MyPage.showAssetWidgetEvents = {};
MyPage.showAssetWidgetRegister = function(handleName, handler) {
    MyPage.showAssetWidgetEvents[handleName] = handler;
}

/**
 * 資産Widget非表示
 * 
 */
MyPage.hideAssetWidget = function() {
    WNote.Asset.hideWidget();
    $.each(MyPage.hideAssetWidgetEvents, function(key, fn) { fn(); });
}
/** 資産Widget非表示イベントハンドラー（利用側で関数を登録） */
MyPage.hideAssetWidgetEvents = {};
MyPage.hideAssetWidgetRegister = function(handleName, handler) {
    MyPage.hideAssetWidgetEvents[handleName] = handler;
}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定一覧取得
 *
 * @param {object} data 検索条件
 */
MyPage.getPlans = function(data) {
    // 在庫集計の取得
    var result = WNote.ajaxValidateSend('/api/picking/api-picking-plan-details/plans', 'POST',　{
        'cond': data.cond
    });

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、出庫予定一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 出庫予定一覧表示
 *
 * @param {object} data 検索条件
 */
MyPage.showPlans = function(data) {
    var result = MyPage.getPlans(data);
    if (result) {
        MyPage.datatable
            .clear()
            .rows.add(result.plans)
            .draw();
    }
}

/**
 * 出庫予定再表示イベント処理
 *
 */
MyPage.reShowPlans = function() {
    MyPage.hideEntryWidget();
    MyPage.hideCancelWidget();
    MyPage.hidePlanWidget();
    MyPage.hideAssetWidget();

    if (MyPage.selectCondition) {
        MyPage.showPlans(MyPage.selectData.condition);
        return;
    }
    MyPage.search();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（検索）
 *  -------------------------------------------------------------------------*/
/**
 * 検索ボタンクリックイベントのハンドラの実装
 */
MyPage.search = function() {
    var data = WNote.Form.createAjaxData(MYPAGE.FORM_KEY);
    MyPage.selectData.condition = data;
    WNote.Util.createFormElements(MYPAGE.FORM_DOWNLOAD, 'cond', data.cond); // ダウンロード用条件の保存
    WNote.Util.removeClassByDataAttr('download', 'disabled');
    MyPage.showPlans(data);
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
