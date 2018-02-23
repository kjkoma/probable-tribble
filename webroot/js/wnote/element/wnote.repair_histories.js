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
const WNOTE_REPAIR_HISTORIES = {
    FORM_KEY      : 'form-elem-repair-histories',
    PREFIX        : 'elemRepairHistories_',
    WIDGET        : '#wid-id-elem-repair-histories',
    FORM_ABROGATE : 'form-elem-repair-histories-abrogate',
    INPUT_ABROGATE: '#elemRepairHistories_abrogate_reason',
    ACTIONS       : {
        INPUT : '#elemRepairHistories-actions',
        END   : '#elemRepairHistories-end-actions'
    },
    VIEW          : {
        KEY    : 'form-elem-repairview',
        PREFIX : 'elemRepairview_'
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.RepairsHistories = {};

/** 選択データ */
WNote.RepairsHistories.selectData = {
    repairId : null,
    repairSts: null,
    history  : {}
}

/** datatableのインスタンス */
WNote.RepairsHistories.datatable = null;

/** フォーム操作用 */
WNote.RepairsHistories.form;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // フォーム操作用インスタンスの作成
    WNote.RepairsHistories.form = new WNote.Lib.Form({
        'add'   : 'elemRepairHistories-add-actions',
        'edit'  : 'elemRepairHistories-edit-actions',
        'delete': 'elemRepairHistories-delete-actions',
        'view'  : 'elemRepairHistories-view-actions'
    });

    /** select2 登録 */
    WNote.Select2.sUser('#elemRepairHistories_history_suser_id', null, false, '記録者選択');

    // 修理履歴一覧テーブル（datatable）初期化
    WNote.RepairsHistories.datatable = $('#elemRepairHistories-datatable').DataTable({
        paging    : false,
        scrollY   : 200,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'history_date'       , width: '10%'  },
            { data: 'history_suser_name' , width: '10%'  },
            { data: 'history_contents'   , width: '80%' }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#elemRepairHistories-datatable tbody').on('click', 'tr', WNote.RepairsHistories.selectedRowHandler);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'elemRepairHistories-edit'    , WNote.RepairsHistories.edit);
    WNote.registerEvent('click', 'elemRepairHistories-add'     , WNote.RepairsHistories.add);
    WNote.registerEvent('click', 'elemRepairHistories-cancel'  , WNote.RepairsHistories.cancel);
    WNote.registerEvent('click', 'elemRepairHistories-save'    , WNote.RepairsHistories.save);
    WNote.registerEvent('click', 'elemRepairHistories-delete'  , WNote.RepairsHistories.delete);

    WNote.registerEvent('click', 'elemRepairHistories-abrogate', WNote.RepairsHistories.abrogate);
    WNote.registerEvent('click', 'elemRepairHistories-complete', WNote.RepairsHistories.complete);

});

/** ---------------------------------------------------------------------------
 *  修理履歴完了 or 廃棄イベント
 *  -------------------------------------------------------------------------*/
/**
 * 修理履歴完了 or 廃棄後のイベントを発行する
 *
 */
WNote.RepairsHistories.afterCompleteOrAbrogate = function() {
    $.each(WNote.RepairsHistories.afterCompleteOrAbrogateEvents, function(key, fn) { fn(); });
}
/** 修理履歴完了 or 廃棄後イベント（利用側で関数を登録） */
WNote.RepairsHistories.afterCompleteOrAbrogateEvents = {};
WNote.RepairsHistories.afterCompleteOrAbrogateRegister = function(handleName, handler) {
    WNote.RepairsHistories.afterCompleteOrAbrogateEvents[handleName] = handler;
}



/** ---------------------------------------------------------------------------
 *  修理履歴表示処理
 *  -------------------------------------------------------------------------*/
/**
 * 修理履歴を表示する
 * 
 * @param {string} repairId  修理ID
 * @param {string} repairSts 修理状況
 */
WNote.RepairsHistories.showWidget = function(repairId, repairSts) {
    WNote.RepairsHistories.selectData.repairId  = repairId;
    WNote.RepairsHistories.selectData.repairSts = repairSts;
    WNote.RepairsHistories.limitActions(WNote.RepairsHistories.selectData.repairSts);
    WNote.RepairsHistories.getRepair(WNote.RepairsHistories.selectData.repairId);
    WNote.RepairsHistories.getHistories(WNote.RepairsHistories.selectData.repairId);
    $(WNOTE_REPAIR_HISTORIES.WIDGET).removeClass('hidden');
}

/**
 * 修理履歴を非表示にする
 */
WNote.RepairsHistories.hideWidget = function() {
    $(WNOTE_REPAIR_HISTORIES.WIDGET).addClass('hidden');
    WNote.RepairsHistories.clear();
}

/**
 * 修理履歴の表示をクリアする
 */
WNote.RepairsHistories.clear = function() {
    WNote.RepairsHistories.selectData.repairId  = null;
    WNote.RepairsHistories.selectData.repairSts = null;
    WNote.RepairsHistories.selectData.history   = null;
    WNote.RepairsHistories.datatable.clear().draw();
    WNote.RepairsHistories.form.before = WNOTE.FORM_STATUS.INIT;
    WNote.RepairsHistories.cancel();
    WNote.Form.clearTexts(WNOTE_REPAIR_HISTORIES.VIEW.KEY);
    WNote.Form.clearFormValues(WNOTE_REPAIR_HISTORIES.FORM_ABROGATE);
}

/**
 * 修理履歴の操作ボタンの表示を制限する
 * @param {string} repairSts 修理状況
 */
WNote.RepairsHistories.limitActions = function(repairSts) {
    $(WNOTE_REPAIR_HISTORIES.ACTIONS.INPUT).addClass('hidden');
    $(WNOTE_REPAIR_HISTORIES.ACTIONS.END).addClass('hidden');

    if (repairSts == $('#elemRepairHistoriesRepairSts_instock').val() ||
        repairSts == $('#elemRepairHistoriesRepairSts_repair').val()) {
        $(WNOTE_REPAIR_HISTORIES.ACTIONS.INPUT).removeClass('hidden');
        $(WNOTE_REPAIR_HISTORIES.ACTIONS.END).removeClass('hidden');
    }
}


/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 修理情報取得
 *
 * @param {string} repairId 修理ID
 */
WNote.RepairsHistories.getRepair = function(repairId) {
    WNote.ajaxFailureMessage = '修理情報の読込に失敗しました。';
    WNote.ajaxSendBasic('/api/repair/api-repairs/repair', 'POST',
        { 'repair_id': repairId },
        false,
        WNote.RepairsHistories.getRepairSuccess
    );
}

/**
 * 修理情報取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.RepairsHistories.getRepairSuccess = function(data) {
    if (data && data.data) {
        WNote.Form.clearTexts(WNOTE_REPAIR_HISTORIES.VIEW.KEY);
        WNote.Form.setTextsWithPrefix(data.data.param.repair, WNOTE_REPAIR_HISTORIES.VIEW.PREFIX);
    }
}

/**
 * 修理履歴一覧取得
 *
 * @param {string} repairId 修理ID
 */
WNote.RepairsHistories.getHistories = function(repairId) {
    WNote.ajaxFailureMessage = '修理履歴の読込に失敗しました。';
    WNote.ajaxSendBasic('/api/repair/api-repair-histories/histories', 'POST',
        { 'repair_id': repairId },
        false,
        WNote.RepairsHistories.getHistoriesSuccess
    );
}

/**
 * 修理履歴一覧取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.RepairsHistories.getHistoriesSuccess = function(data) {
    if (data && data.data) {
        WNote.RepairsHistories.datatable
            .clear()
            .rows.add(data.data.param.histories)
            .draw();
    }
}

/**
 * 修理履歴取得
 *
 * @param {string} historyId 修理履歴ID
 */
WNote.RepairsHistories.getHistory = function(historyId) {
    WNote.ajaxFailureMessage = '修理履歴の読込に失敗しました。';
    WNote.ajaxSendBasic('/api/repair/api-repair-histories/history', 'POST',
        { 'id': historyId },
        false,
        WNote.RepairsHistories.getHistorySuccess
    );
}

/**
 * 修理履歴取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.RepairsHistories.getHistorySuccess = function(data) {
    if (data && data.data) {
        WNote.RepairsHistories.selectData.history = data.data.param.history;
        WNote.RepairsHistories.setFormValues();
    }
}

/**
 * 修理履歴をフォームに表示する
 * 
 */
WNote.RepairsHistories.setFormValues = function() {
    WNote.Form.clearFormValues(WNOTE_REPAIR_HISTORIES.FORM_KEY);
    WNote.Form.setFormValuesWithPrefix(WNote.RepairsHistories.selectData.history, WNOTE_REPAIR_HISTORIES.PREFIX);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（修理履歴選択）
 *  -------------------------------------------------------------------------*/
/**
 * 資産選択イベントのハンドラの実装
 */
WNote.RepairsHistories.selectedRowHandler = function() {
    var selected = WNote.RepairsHistories.datatable.row( this ).data();
    if (selected) {
        WNote.Util.All.highlightDataTableRow($(this), WNote.RepairsHistories.datatable);
        WNote.RepairsHistories.form.viewMode(WNOTE_REPAIR_HISTORIES.FORM_KEY);
        WNote.RepairsHistories.getHistory(selected.id);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（編集画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 編集ボタンクリックイベントのハンドラの実装
 */
WNote.RepairsHistories.edit = function() {
    WNote.RepairsHistories.form.editMode(WNOTE_REPAIR_HISTORIES.FORM_KEY);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（追加画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 追加ボタンクリックイベントのハンドラの実装
 */
WNote.RepairsHistories.add = function() {
    WNote.Form.clearFormValues(WNOTE_REPAIR_HISTORIES.FORM_KEY);
    WNote.RepairsHistories.form.addMode(WNOTE_REPAIR_HISTORIES.FORM_KEY);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（キャンセル）
 *  -------------------------------------------------------------------------*/
/**
 * キャンセルボタンクリックイベントのハンドラの実装
 */
WNote.RepairsHistories.cancel = function() {
    WNote.RepairsHistories.form.validateClear();

    if (WNote.RepairsHistories.form.before == WNOTE.FORM_STATUS.INIT) {
        WNote.Form.clearFormValues(WNOTE_REPAIR_HISTORIES.FORM_KEY);
        WNote.RepairsHistories.form.initMode(WNOTE_REPAIR_HISTORIES.FORM_KEY);
    } else {
        WNote.RepairsHistories.setFormValues();
        WNote.RepairsHistories.form.viewMode(WNOTE_REPAIR_HISTORIES.FORM_KEY);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリックイベントのハンドラの実装
 */
WNote.RepairsHistories.save = function() {
    var saveUrl  = '/api/repair/api-repair-histories/';
    var saveType = 'add';
    var successHandler = WNote.RepairsHistories.saveAddSuccess;

    if (WNote.RepairsHistories.form.current == WNOTE.FORM_STATUS.EDIT) {
        saveType       = 'edit';
        successHandler = WNote.RepairsHistories.saveEditSuccess;
    }

    // 送信データ作成
    var data = WNote.Form.createAjaxData(WNOTE_REPAIR_HISTORIES.FORM_KEY);
    data.history           = data['elem-repair-histories'];
    data.history.id        = WNote.RepairsHistories.selectData.history.id;
    data.history.repair_id = WNote.RepairsHistories.selectData.repairId;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, WNote.RepairsHistories.saveValidate(saveType, data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '入力された内容の保存に失敗しました。再度保存してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic(saveUrl + saveType, 'POST',
        data,
        true,
        successHandler
    );

}
/**
 * 保存ボタンクリック成功時のハンドラの実装（追加時）
 *
 * @param {object} data レスポンスデータ
 */
WNote.RepairsHistories.saveAddSuccess = function(data) {
WNote.log(data); // debug
    WNote.ajaxSuccessHandler(data, '入力された内容を登録しました。');
    WNote.RepairsHistories.form.validateClear();
    WNote.Form.clearFormValues(WNOTE_REPAIR_HISTORIES.FORM_KEY);
    WNote.RepairsHistories.form.initMode(WNOTE_REPAIR_HISTORIES.FORM_KEY);
    WNote.RepairsHistories.getHistories(WNote.RepairsHistories.selectData.repairId);
}
/**
 * 保存ボタンクリック成功時のハンドラの実装（編集時）
 *
 * @param {object} data レスポンスデータ
 */
WNote.RepairsHistories.saveEditSuccess = function(data) {
WNote.log(data); // debug
    WNote.RepairsHistories.form.validateClear();
    WNote.RepairsHistories.form.viewMode(WNOTE_REPAIR_HISTORIES.FORM_KEY);
    WNote.ajaxSuccessHandler(data, '入力された内容を保存しました。');
}

/**
 * モデルデータ保存時の検証を行う
 *
 * @param {string} saveType 保存タイプ（add or edit）
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
WNote.RepairsHistories.saveValidate = function(saveType, data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // history_date
    options.rules['elem-repair-histories.history_date']    = { required: true, date: true, dateFormat: true };
    options.messages['elem-repair-histories.history_date'] = { required: '履歴日を入力してください。', date: '有効な履歴日をyyyy/mm/ddの形式で入力してください。', dateFormat: '履歴日はyyyy/mm/dd形式で入力してください。' };

    // history_contents
    options.rules['elem-repair-histories.history_contents']    = { maxlength: 2048 };
    options.messages['elem-repair-histories.history_contents'] = { maxlength: '修理内容は最大2048文字で入力してください。' };

    // remarks
    options.rules['elem-repair-histories.remarks']    = { maxlength: 2048 };
    options.messages['elem-repair-histories.remarks'] = { maxlength: '補足は最大2048文字で入力してください。' };

    WNote.RepairsHistories.form.validateClear();
    WNote.RepairsHistories.form.validator = $('#' + WNOTE_REPAIR_HISTORIES.FORM_KEY).validate(options);
    WNote.RepairsHistories.form.validator.form();
    if (!WNote.RepairsHistories.form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    if (saveType == "edit" && (!data || data == '')) {
        message = 'モデルが選択されていません。モデルを選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（削除）
 *  -------------------------------------------------------------------------*/
/**
 * 削除ボタンクリックイベントのハンドラの実装
 */
WNote.RepairsHistories.delete = function() {
    WNote.showConfirmMessage('修理履歴を削除すると復元することができません。本当に削除してもよろしいですか？');
    WNote.showConfirmYesHandler = WNote.RepairsHistories.deleteHistory;
}
/**
 * 削除ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.RepairsHistories.deleteSuccess = function(data) {
WNote.log(data); // debug
    WNote.Form.clearFormValues(WNOTE_REPAIR_HISTORIES.FORM_KEY);
    WNote.RepairsHistories.form.initMode(WNOTE_REPAIR_HISTORIES.FORM_KEY);
    WNote.ajaxSuccessHandler(data, '選択されたデータが正常に削除されました。');
    WNote.RepairsHistories.getHistories(WNote.RepairsHistories.selectData.repairId);
}

/**
 * 修理履歴データを削除する
 *
 */
WNote.RepairsHistories.deleteHistory = function() {
    // 送信データ作成
    var historyId = WNote.RepairsHistories.selectData.history.id;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(historyId, WNote.RepairsHistories.deleteValidate(historyId))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = 'モデルデータの削除に失敗しました。再度削除してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/repair/api-repair-histories/delete', 'POST',
        { 'id': historyId },
        true,
        WNote.RepairsHistories.deleteSuccess
    );
}

/**
 * 修理履歴データ削除時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
WNote.RepairsHistories.deleteValidate = function(data) {
    var message;

    if (!data || data == '') {
        message = '修理履歴が選択されていません。修理履歴を選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（修理完了）
 *  -------------------------------------------------------------------------*/
/**
 * 修理完了ボタンクリックイベントのハンドラの実装
 */
WNote.RepairsHistories.complete = function() {
    WNote.showConfirmMessage('修理を完了してもよろしいですか？出庫依頼より作成時は出庫作業中となり、修理登録時は資産状況が在庫となります。');
    WNote.showConfirmYesHandler = WNote.RepairsHistories.completeRepair;
}
/**
 * 修理完了ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.RepairsHistories.completeSuccess = function(data) {
WNote.log(data); // debug
    WNote.ajaxSuccessHandler(data, '選択された修理データが完了に更新されました。');
    WNote.RepairsHistories.hideWidget();
    WNote.RepairsHistories.afterCompleteOrAbrogate();
}

/**
 * 修理を完了する
 *
 */
WNote.RepairsHistories.completeRepair = function() {
    // 送信データ作成
    var repairId = WNote.RepairsHistories.selectData.repairId;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(repairId, WNote.RepairsHistories.completeValidate(repairId))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '修理完了の更新に失敗しました。再度完了してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/repair/api-repairs/complete', 'POST',
        { 'id': repairId },
        true,
        WNote.RepairsHistories.completeSuccess
    );
}

/**
 * 修理完了時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
WNote.RepairsHistories.completeValidate = function(data) {
    var message;

    if (!data || data == '') {
        message = '修理データが選択されていません。修理データを選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（廃棄）
 *  -------------------------------------------------------------------------*/
/**
 * 廃棄予定に追加するボタンクリックイベントのハンドラの実装
 */
WNote.RepairsHistories.abrogate = function() {
    WNote.showConfirmMessage('廃棄予定に追加してもよろしいですか？本修理は完了となり、資産（在庫）が廃棄予定に追加されます。');
    WNote.showConfirmYesHandler = WNote.RepairsHistories.abrogateRepair;
}
/**
 * 廃棄予定に追加するボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.RepairsHistories.abrogateSuccess = function(data) {
WNote.log(data); // debug
    WNote.ajaxSuccessHandler(data, '選択された修理データが正常に廃棄予定に追加されました。');
    WNote.RepairsHistories.hideWidget();
    WNote.RepairsHistories.afterCompleteOrAbrogate();
}

/**
 * 廃棄予定に追加する
 *
 */
WNote.RepairsHistories.abrogateRepair = function() {
    // 送信データ作成
    var data = {};
    data.id             = WNote.RepairsHistories.selectData.repairId;
    data.abrogateReason = $(WNOTE_REPAIR_HISTORIES.INPUT_ABROGATE).val();

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, WNote.RepairsHistories.abrogateValidate(data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '廃棄予定の追加に失敗しました。再度完了してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/repair/api-repairs/abrogate', 'POST',
        {
            'id'              : data.id,
            'abrogate_reason' : data.abrogateReason

        },
        true,
        WNote.RepairsHistories.abrogateSuccess
    );
}

/**
 * 廃棄予定追加時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
WNote.RepairsHistories.abrogateValidate = function(data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // abrogate_reason
    options.rules['elem-repair-abrogate.abrogate_reason']    = { required: true, maxlength: 2048 };
    options.messages['elem-repair-abrogate.abrogate_reason'] = { required: '廃棄理由を入力してください。', maxlength: '廃棄理由は2048文字以内で入力してください。' };

    WNote.RepairsHistories.form.validateClear();
    WNote.RepairsHistories.form.validator = $('#' + WNOTE_REPAIR_HISTORIES.FORM_KEY).validate(options);
    WNote.RepairsHistories.form.validator.form();
    if (!WNote.RepairsHistories.form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    if (!data || data.id == '') {
        message = '修理データが選択されていません。修理データを選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}
