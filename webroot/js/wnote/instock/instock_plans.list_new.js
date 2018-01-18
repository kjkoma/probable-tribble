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
    FORM_KEY    : "form-plan",
    INSTOCK_KBN : "#instock_kbn"
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中の入庫予定データ */
MyPage.selectPlan = {
    plan: {}
};

/** 入庫区分 */
MyPage.instockKbn = {
    current  : -1,
    new      : -1
}

/** datatableのインスタンス */
MyPage.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 入庫区分設定
    MyPage.instockKbn.new     = $('#instock_kbn_new').val();
    MyPage.instockKbn.current = MyPage.instockKbn.new;

    // 入庫予定一覧テーブル（datatable）初期化
    MyPage.datatable = $('#plans-datatable').DataTable({
        paging    : false,
        scrollY   : 200,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'instock_kbn_name' , width: '8%' },
            { data: 'plan_date'        , width: '8%' },
            { data: 'plan_sts_name'    , width: '8%' },
            { data: 'name'             , width: '30%' },
            { data: 'remarks'          , width: '46%' }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#plans-datatable tbody').on('click', 'tr', MyPage.selectedPlanHandler);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'edit'   , MyPage.edit);
    WNote.registerEvent('click', 'add'    , MyPage.add);
    WNote.registerEvent('click', 'cancel' , MyPage.cancel);
    WNote.registerEvent('click', 'save'   , MyPage.save);
    WNote.registerEvent('click', 'delete' , MyPage.delete);

    /* 入庫予定一覧を表示 */
    MyPage.showPlans();
});

/** ---------------------------------------------------------------------------
 *  イベント処理（入庫予定詳細ライブラリ（instock_plans.list_new.details.js）側で実装）
 *  -------------------------------------------------------------------------*/
/**
 * 入庫予定詳細一覧表示（入庫予定詳細ライブラリ内で実装）
 *
 * @param {string} plan_id 入庫予定ID
 */
MyPage.showDetailTable = function(plan_id) {}

/**
 * 入庫予定詳細クリア（入庫予定詳細ライブラリ内で実装）
 *
 */
MyPage.clearDetailTable = function() {}

/**
 * 入庫予定詳細表示（入庫予定詳細ライブラリ内で実装）
 *
 * @param {string} plan_id 入庫予定ID
 */
MyPage.showDetails = function(plan_id) {}

/**
 * 入庫予定詳細非表示化（入庫予定詳細ライブラリ内で実装）
 *
 */
MyPage.hideDetails = function() {}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 入庫予定一覧取得
 *
 */
MyPage.getPlans = function() {
    // 入庫予定一覧の取得
    var result = WNote.ajaxValidateSend('/api/instock/api-instock-plans/plans-new', 'POST',　{});

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

/**
 * 入庫予定取得
 *
 * @param {integer} plan_id 入庫予定ID
 */
MyPage.getPlan = function(plan_id) {
    // 入庫予定データの取得
    WNote.ajaxFailureMessage = '入庫予定データの読込に失敗しました。再度入庫予定を選択してください。';
    WNote.ajaxSendBasic('/api/instock/api-instock-plans/plan', 'POST',
        { 'plan_id': plan_id },
        true,
        MyPage.getPlanSuccess
    );
}

/**
 * 入庫予定取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.getPlanSuccess = function(data) {
    MyPage.selectPlan.plan = data.data.param.plan;
    WNote.Form.viewMode(MYPAGE.FORM_KEY);
    WNote.hideLoading();
    MyPage.setFormValues();
    MyPage.showDetailTable(MyPage.selectPlan.plan.id);
}

/**
 * 入庫予定データをフォームに表示する
 * 
 */
MyPage.setFormValues = function() {
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.setFormValues(MyPage.selectPlan.plan);
    MyPage.controlInputKbn();
    MyPage.showDetails();
}

/**
 * 入庫区分による制御を行う
 * 
 */
MyPage.controlInputKbn = function() {
    MyPage.instockKbn.current = MyPage.selectPlan.plan.instock_kbn;
    if (MyPage.instockKbn.current == MyPage.instockKbn.new) {
        MyPage.selectInputKbnNewHandler();
    } else {
        MyPage.selectInputKbnBackHandler();
    }
}
/**
 * 入庫区分に新規が選択された場合のイベントハンドラー
 * 
 */
MyPage.selectInputKbnNewHandler = function() {}

/**
 * 入庫区分に返却が選択された場合のイベントハンドラー
 * 
 */
MyPage.selectInputKbnBackHandler = function() {}

/** ---------------------------------------------------------------------------
 *  イベント処理（予定選択）
 *  -------------------------------------------------------------------------*/
MyPage.selectedPlanHandler = function() {
    var selected = MyPage.datatable.row( this ).data();
    if (selected) {
        MyPage.getPlan(selected.id);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（編集画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 編集ボタンクリックイベントのハンドラの実装
 */
MyPage.edit = function() {
    WNote.Form.editMode(MYPAGE.FORM_KEY);
    $(MYPAGE.INSTOCK_KBN).prop('disabled', true);
    MyPage.hideDetails();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（追加画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 追加ボタンクリックイベントのハンドラの実装
 */
MyPage.add = function() {
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.addMode(MYPAGE.FORM_KEY);
    $(MYPAGE.INSTOCK_KBN).val(MyPage.instockKbn.new);
    $(MYPAGE.INSTOCK_KBN).prop('disabled', false);
    MyPage.hideDetails();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（キャンセル）
 *  -------------------------------------------------------------------------*/
/**
 * キャンセルボタンクリックイベントのハンドラの実装
 */
MyPage.cancel = function() {
    WNote.Form.validateClear();

    if (WNote.Form.formActionStatus.Before == WNOTE.FORM_STATUS.INIT) {
        WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
        WNote.Form.initMode(MYPAGE.FORM_KEY);
        MyPage.hideDetails();
        MyPage.clearDetailTable();
    } else {
        MyPage.setFormValues();
        WNote.Form.viewMode(MYPAGE.FORM_KEY);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリックイベントのハンドラの実装
 */
MyPage.save = function() {
    var saveUrl  = '/api/instock/api-instock-plans/';
    var saveType = 'add';
    var successHandler = MyPage.saveAddSuccess;

    if (WNote.Form.formActionStatus.Current == WNOTE.FORM_STATUS.EDIT) {
        saveType       = 'edit';
        successHandler = MyPage.saveEditSuccess;
    }

    // 送信データ作成
    var data = WNote.Form.createAjaxData(MYPAGE.FORM_KEY);

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.saveValidate(saveType, data))) {
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
MyPage.saveAddSuccess = function(data) {
WNote.log(data); // debug
    MyPage.getPlan(data.data.param.plan.id);
    WNote.ajaxSuccessHandler(data, '入力された内容を登録しました。');
    MyPage.showPlans();
}
/**
 * 保存ボタンクリック成功時のハンドラの実装（編集時）
 *
 * @param {object} data レスポンスデータ
 */
MyPage.saveEditSuccess = function(data) {
WNote.log(data); // debug
    WNote.Form.viewMode(MYPAGE.FORM_KEY);
    WNote.Form.validateClear();
    WNote.ajaxSuccessHandler(data, '入力された内容を保存しました。');
    MyPage.showPlans();
    MyPage.showDetails();
}

/**
 * 入庫予定データ保存時の検証を行う
 *
 * @param {string} saveType 保存タイプ（add or edit）
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.saveValidate = function(saveType, data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // plan_date
    options.rules['plan.plan_date']    = { required: true, date: true };
    options.messages['plan.plan_date'] = { required: '入庫予定日を入力してください。', date: '有効な入庫予定日をyyyy/mm/ddの形式で入力してください。' };

    // name
    options.rules['plan.name']    = { required: true, maxlength: 60 };
    options.messages['plan.name'] = { required: '表示名を入力してください。', maxlength: '表示名は最大60文字で入力してください。' };

    // remarks
    options.rules['plan.remarks']    = { maxlength: 2048 };
    options.messages['plan.remarks'] = { maxlength: '備考は最大2048文字で入力してください。' };

    // 入力内容の検証
    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        return WNote.Form.validateResultSet('入力内容に不備があります。各入力内容をご確認ください。');
    }

    // 選択していないケース（※存在してはならないが・・）の検証
    if (saveType == "edit" && (!data || data == '')) {
        return WNote.Form.validateResultSet('入庫予定が選択されていません。入庫予定を選択してください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（削除）
 *  -------------------------------------------------------------------------*/
/**
 * 削除ボタンクリックイベントのハンドラの実装
 */
MyPage.delete = function() {
    WNote.showConfirmMessage('入庫予定を削除すると入庫予定に関連するデータがすべて削除され、復元することができません。本当に削除してもよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.deletePlan;
}
/**
 * 削除ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.deleteSuccess = function(data) {
WNote.log(data); // debug
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.initMode(MYPAGE.FORM_KEY);
    WNote.ajaxSuccessHandler(data, '選択されたデータが正常に削除されました。');
    MyPage.hideDetails();
    MyPage.clearDetailTable();
    MyPage.showPlans();
}

/**
 * 入庫予定データを削除する
 *
 */
MyPage.deletePlan = function() {
    // 送信データ作成
    var plan_id = (MyPage.selectPlan.plan['id']) ? MyPage.selectPlan.plan['id'] : undefined;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(plan_id, MyPage.deleteValidate(plan_id))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '入庫予定データの削除に失敗しました。再度削除してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/instock/api-instock-plans/delete', 'POST',
        { 'id': plan_id },
        true,
        MyPage.deleteSuccess
    );
}

/**
 * 入庫予定データ削除時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.deleteValidate = function(data) {
    var message;

    if (!data || data == '') {
        message = '入庫予定が選択されていません。入庫予定を選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}
