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
    FORM_KEY: 'form-plan',
    CONTENTS: {
        EXCHANGE: '#exchange_contents',
        REPAIR  : '#repair_contents'
    },
    PREFIX: {
        EXCHANGE: 'exchange_',
        REPAIR  : 'repair_'
    },
    PICKING_KBN    : '#picking_kbn'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中の出庫依頼データ */
MyPage.selectPlan = {
    plan    : {},
    exchange: {},
    repair  : {}
};

/** datatableのインスタンス */
MyPage.datatable = null;

/** 出庫区分 */
MyPage.pickingKbn = {
    current  : -1,
    new      : -1,
    exchange : -1,
    repair   : -1,
}

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 出庫区分設定
    MyPage.pickingKbn.new      = $('#picking_kbn_new').val();
    MyPage.pickingKbn.exchange = $('#picking_kbn_exchange').val();
    MyPage.pickingKbn.repair   = $('#picking_kbn_repair').val();
    MyPage.pickingKbn.current  = MyPage.pickingKbn.new;

    // 出庫予定一覧テーブル（datatable）初期化
    MyPage.datatable = $('#plans-datatable').DataTable({
        paging    : false,
        scrollY   : 300,
        scrollX   : false,
        searching : true,
        ordering  : false,
        language  : {
            search : '一覧内フィルタ　:　'
        },
        columns   : [
            { data: 'picking_kbn_name' , width: '6%' },
            { data: 'plan_sts_name'    , width: '8%' },
            { data: 'apply_no'         , width: '10%' },
            { data: 'req_date'         , width: '8%' },
            { data: 'req_user_name'    , width: '13%' },
            { data: 'use_user_name'    , width: '13%' },
            { data: 'dlv_user_name'    , width: '13%' },
            { data: 'rcv_suser_name'   , width: '13%' },
            { data: 'category_name'    , width: '16%' }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#plans-datatable tbody').on('click', 'tr', MyPage.selectedPlanHandler);

    /** select2 登録 */
    WNote.Select2.organization('#req_organization_id' , null                      , true, '依頼者（組織）');
    WNote.Select2.user('#req_user_id'                 , '#req_organization_id'    , true, '依頼者（ユーザー）');
    WNote.Select2.organization('#use_organization_id' , null                      , true, '使用者（組織）');
    WNote.Select2.user('#use_user_id'                 , '#use_organization_id'    , true, '使用者（ユーザー）');
    WNote.Select2.organization('#dlv_organization_id' , null                      , true, '出庫先（組織）');
    WNote.Select2.user('#dlv_user_id'                 , '#dlv_organization_id'    , true, '出庫先（ユーザー）');
    WNote.Select2.sUser('#rcv_suser_id'               , null                      , true, '受付者');
    WNote.Select2.kittingPattern('#kitting_pattern_id', {reuse_kbn: '#reuse_kbn'} , true, 'キッティングパターン');


    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'edit'   , MyPage.edit);
    WNote.registerEvent('click', 'add'    , MyPage.add);
    WNote.registerEvent('click', 'cancel' , MyPage.cancel);
    WNote.registerEvent('click', 'save'   , MyPage.save);
    WNote.registerEvent('click', 'delete' , MyPage.delete);

    /** 出庫区分変更イベント登録 */
    WNote.registerEvent('change', 'change-picking_kbn' , MyPage.changePickingKbn);

    /* 出庫予定一覧を表示 */
    MyPage.showPlans();
});

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定（依頼）一覧取得
 *
 */
MyPage.getPlans = function() {
    // 出庫予定一覧の取得
    var result = WNote.ajaxValidateSend('/api/picking/api-picking-plans/plans-request', 'POST',　{});

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、出庫予定の一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 出庫予定一覧表示
 *
 */
MyPage.showPlans = function() {
    var result = MyPage.getPlans();
    if (result) {
        MyPage.datatable
            .clear()
            .rows.add(result.plans)
            .draw();
    }
}

/**
 * 出庫予定取得
 *
 * @param {integer} planId 出庫予定ID
 */
MyPage.getPlan = function(planId) {
    // 出庫予定データの取得
    WNote.ajaxFailureMessage = '出庫予定データの読込に失敗しました。再度出庫予定を選択してください。';
    WNote.ajaxSendBasic('/api/picking/api-picking-plans/plan', 'POST',
        { 'plan_id': planId },
        true,
        MyPage.getPlanSuccess
    );
}

/**
 * 出庫予定取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.getPlanSuccess = function(data) {
    MyPage.selectPlan.plan     = data.data.param.plan;
    MyPage.selectPlan.exchange = data.data.param.exchange;
    MyPage.selectPlan.repair   = data.data.param.repair;
    WNote.Form.viewMode(MYPAGE.FORM_KEY);
    WNote.hideLoading();
    MyPage.setFormValues();
}

/**
 * 出庫予定データをフォームに表示する
 * 
 */
MyPage.setFormValues = function() {
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.setFormValues(MyPage.selectPlan.plan);
    WNote.Form.setFormValuesWithPrefix(MyPage.selectPlan.exchange, MYPAGE.PREFIX.EXCHANGE);
    WNote.Form.setFormValuesWithPrefix(MyPage.selectPlan.repair, MYPAGE.PREFIX.REPAIR);
    MyPage.changePickingKbn();
}

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
 *  イベント処理（出庫区分変更）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫区分変更イベントのハンドラの実装
 */
MyPage.changePickingKbn = function() {
    MyPage.pickingKbn.current = $(MYPAGE.PICKING_KBN).val();

    switch (MyPage.pickingKbn.current) {
        case MyPage.pickingKbn.new :
            $(MYPAGE.CONTENTS.EXCHANGE).addClass('hidden');
            $(MYPAGE.CONTENTS.REPAIR).addClass('hidden');
            break;
        case MyPage.pickingKbn.exchange :
            $(MYPAGE.CONTENTS.EXCHANGE).removeClass('hidden');
            $(MYPAGE.CONTENTS.REPAIR).addClass('hidden');
            break;
        case MyPage.pickingKbn.repair :
            $(MYPAGE.CONTENTS.EXCHANGE).addClass('hidden');
            $(MYPAGE.CONTENTS.REPAIR).removeClass('hidden');
            break;
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（モード処理拡張）
 *  -------------------------------------------------------------------------*/
/** 表示モード処理拡張用（実装） */
WNote.Form.viewModeExtend = function() {
    // 未出庫以外の場合は編集を不可にする
    if (MyPage.selectPlan.plan.plan_sts != $('#plan_sts_not').val()) {
        WNote.Util.addClassByDataAttr('view-actions', 'hidden');
    } else {
        WNote.Util.removeClassByDataAttr('view-actions', 'hidden');
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
    $(MYPAGE.PICKING_KBN).attr('disabled', 'disabled');
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
    } else {
        MyPage.setFormValues();
        WNote.Form.viewMode(MYPAGE.FORM_KEY);
    }

    MyPage.changePickingKbn();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリックイベントのハンドラの実装
 */
MyPage.save = function() {
    var saveUrl  = '/api/picking/api-picking-plans/';
    var saveType = 'add';
    var successHandler = MyPage.saveAddSuccess;

    if (WNote.Form.formActionStatus.Current == WNOTE.FORM_STATUS.EDIT) {
        saveType       = 'edit';
        successHandler = MyPage.saveEditSuccess;
    }

    // 送信データ作成
    var data = WNote.Form.createAjaxData(MYPAGE.FORM_KEY);

    // 交換・修理時は資産管理番号、シリアル番号を付加する
    if (MyPage.pickingKbn.current == MyPage.pickingKbn.exchange) {
        data.serial_no = data.exchange.serial_no;
        data.asset_no  = data.exchange.asset_no;
        if (saveType == 'edit') data.exchange.id = MyPage.selectPlan.exchange.id;
    }
    if (MyPage.pickingKbn.current == MyPage.pickingKbn.repair) {
        data.serial_no = data.repair.serial_no;
        data.asset_no  = data.repair.asset_no;
        if (saveType == 'edit') data.repair.id = MyPage.selectPlan.repair.id;
    }

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
}

/**
 * 出庫予定データ保存時の検証を行う
 *
 * @param {string} saveType 保存タイプ（add or edit）
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.saveValidate = function(saveType, data) {
    // 編集可否検証
    if (saveType == "edit" && !MyPage.validateEditCancel()) {
        return WNote.Form.validateResultSet('編集対象の予定はすでに未出庫以外の状態の為、編集を行うことはできません。');
    }
    if (saveType == "edit" && MyPage.pickingKbn.current != MyPage.pickingKbn.new && !MyPage.validateInstockPlan()) {
        return WNote.Form.validateResultSet('取消対象の予定に対応する入庫予定がすでに未入庫以外の状態の為、編集を行うことはできません。');
    }

    var options = WNote.Util.Validate.ValidatorOptions();

    // picking_kbn
    options.rules['plan.picking_kbn']    = { required: true };
    options.messages['plan.picking_kbn'] = { required: '出庫区分を選択してください。' };

    // apply_no
    options.rules['plan.apply_no']    = { maxlength: 60 };
    options.messages['plan.apply_no'] = { maxlength: '表示名は最大60文字で入力してください。' };

    // req_date
    options.rules['plan.req_date']    = { required: true, date: true };
    options.messages['plan.req_date'] = { required: '依頼日(申請日)を入力してください。', date: '有効な依頼日(申請日)をyyyy/mm/ddの形式で入力してください。' };

    // req_user_id
    options.rules['plan.req_user_id']    = { required: true };
    options.messages['plan.req_user_id'] = { required: '依頼者(ユーザー)を入力してください。' };

    // req_emp_no
    options.rules['plan.req_emp_no']    = { maxlength: 20 };
    options.messages['plan.req_emp_no'] = { maxlength: '依頼者(社員番号)は最大20文字で入力してください。' };

    // req_user_id
    options.rules['plan.use_user_id']    = { required: true };
    options.messages['plan.use_user_id'] = { required: '使用者(ユーザー)を入力してください。' };

    // use_emp_no
    options.rules['plan.use_emp_no']    = { maxlength: 20 };
    options.messages['plan.use_emp_no'] = { maxlength: '使用者(社員番号)は最大20文字で入力してください。' };

    // dlv_user_id
    options.rules['plan.dlv_user_id']    = { required: true };
    options.messages['plan.dlv_user_id'] = { required: '出庫先((ユーザー)を入力してください。' };

    // dlv_emp_no
    options.rules['plan.dlv_emp_no']    = { maxlength: 20 };
    options.messages['plan.dlv_emp_no'] = { maxlength: '出庫先((社員番号)は最大20文字で入力してください。' };

    // dlv_name
    options.rules['plan.dlv_name']    = { maxlength: 60 };
    options.messages['plan.dlv_name'] = { maxlength: '出庫先(宛)は最大60文字で入力してください。' };

    // dlv_tel
    options.rules['plan.dlv_tel']    = { required: true, maxlength: 20 };
    options.messages['plan.dlv_tel'] = { required: '出庫先(連絡先)を入力してください。', maxlength: '出庫先(連絡先)は最大20文字で入力してください。' };

    // dlv_zip
    options.rules['plan.dlv_zip']    = { maxlength: 7 };
    options.messages['plan.dlv_zip'] = { maxlength: '郵便番号は7文字（例：1010025）で入力してください。' };

    // dlv_address
    options.rules['plan.arv_date']    = { required: true, maxlength: 20 };
    options.messages['plan.arv_date'] = { required: '出庫先(住所)を入力してください。', maxlength: '出庫先(住所)は最大120文字で入力してください。' };

    // arv_date
    options.rules['plan.arv_date']    = { date: true };
    options.messages['plan.arv_date'] = { date: '有効な到着希望日をyyyy/mm/ddの形式で入力してください。' };

    // arv_remarks
    options.rules['plan.arv_remarks']    = { maxlength: 2048 };
    options.messages['plan.arv_remarks'] = { maxlength: '到着希望メモは最大2048文字で入力してください。' };

    // rcv_date
    options.rules['plan.rcv_date']    = { required: true, date: true };
    options.messages['plan.rcv_date'] = { required: '受付日を入力してください。', date: '有効な受付日をyyyy/mm/ddの形式で入力してください。' };

    // rcv_suser_id
    options.rules['plan.rcv_suser_id']    = { required: true };
    options.messages['plan.rcv_suser_id'] = { required: '受付者を入力してください。' };

    // picking_reason
    options.rules['plan.picking_reason']    = { maxlength: 2048 };
    options.messages['plan.picking_reason'] = { maxlength: '出庫理由は最大2048文字で入力してください。' };

    // 交換時追加チェック
    if (MyPage.pickingKbn.current == MyPage.pickingKbn.exchange) {
        // instock_plan_date
        options.rules['exchange.instock_plan_date']    = { required: true, date: true };
        options.messages['exchange.instock_plan_date'] = { required: '入庫予定日を入力してください。', date: '有効な入庫予定日をyyyy/mm/ddの形式で入力してください。'  };

        // asset_no
        options.rules['exchange.asset_no']    = { required: MyPage.validateSerialAndAssetNo, maxlength: 60, validateAssetNo: true };
        options.messages['exchange.asset_no'] = { required: '資産管理番号、シリアル番号のいずれかを入力してください。', maxlength: '資産管理番号は最大60文字で入力してください。' };

        // serial_no
        options.rules['exchange.serial_no']    = { required: MyPage.validateSerialAndAssetNo, maxlength: 120, validateSerialNo: true };
        options.messages['exchange.serial_no'] = { required: '資産管理番号、シリアル番号のいずれかを入力してください。', maxlength: 'シリアル番号は最大120文字で入力してください。' };

        // exchange_reason
        options.rules['exchange.exchange_reason']    = { required: true, maxlength: 2048 };
        options.messages['exchange.exchange_reason'] = { required: '交換理由を入力してください。', maxlength: '交換理由は最大2048文字で入力してください。' };
    }

    // 修理時追加チェック
    if (MyPage.pickingKbn.current == MyPage.pickingKbn.repair) {
        // instock_plan_date
        options.rules['repair.instock_plan_date']    = { required: true, date: true };
        options.messages['repair.instock_plan_date'] = { required: '入庫予定日を入力してください。', date: '有効な入庫予定日をyyyy/mm/ddの形式で入力してください。'  };

        // asset_no
        options.rules['repair.asset_no']    = { required: MyPage.validateSerialAndAssetNo, maxlength: 60, validateAssetNo: true };
        options.messages['repair.asset_no'] = { required: '資産管理番号、シリアル番号のいずれかを入力してください。', maxlength: '資産管理番号は最大60文字で入力してください。' };

        // serial_no
        options.rules['repair.serial_no']    = { required: MyPage.validateSerialAndAssetNo, maxlength: 120, validateSerialNo: true };
        options.messages['repair.serial_no'] = { required: '資産管理番号、シリアル番号のいずれかを入力してください。', maxlength: 'シリアル番号は最大120文字で入力してください。' };

        // trouble_kbn
        options.rules['repair.trouble_kbn']    = { required: true };
        options.messages['repair.trouble_kbn'] = { required: '故障区分を入力してください。'  };

        // sendback_kbn
        options.rules['repair.sendback_kbn']    = { required: true };
        options.messages['repair.sendback_kbn'] = { required: 'センドバック有無を入力してください。'  };

        // trouble_kbn
        options.rules['repair.datapick_kbn']    = { required: true };
        options.messages['repair.datapick_kbn'] = { required: 'データ抽出有無を入力してください。'  };

        // trouble_reason
        options.rules['repair.trouble_reason']    = { required: true, maxlength: 2048 };
        options.messages['repair.trouble_reason'] = { required: '交換理由を入力してください。', maxlength: '交換理由は最大2048文字で入力してください。' };
    }

    // 入力内容の検証
    if (WNote.Form.validator) WNote.Form.validator.destroy();
    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        return WNote.Form.validateResultSet('入力内容に不備があります。各入力内容をご確認ください。');
    }

    // 選択していないケース（※存在してはならないが・・）の検証
    if (saveType == "edit" && (!data || data == '')) {
        return WNote.Form.validateResultSet('出庫予定が選択されていません。出庫予定を選択してください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（取消）
 *  -------------------------------------------------------------------------*/
/**
 * 取消ボタンクリックイベントのハンドラの実装
 */
MyPage.delete = function() {
    WNote.showConfirmMessage('出庫依頼を本当に取消してもよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.deletePlan;
}
/**
 * 取消ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.deleteSuccess = function(data) {
WNote.log(data); // debug
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.initMode(MYPAGE.FORM_KEY);
    WNote.ajaxSuccessHandler(data, '選択されたデータが正常に取消されました。');
    MyPage.showPlans();
}

/**
 * 出庫予定データを取消する
 *
 */
MyPage.deletePlan = function() {
    // 送信データ作成
    var planId = (MyPage.selectPlan.plan['id']) ? MyPage.selectPlan.plan['id'] : undefined;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(planId, MyPage.deleteValidate(planId))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '出庫予定データの取消に失敗しました。再度取消してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/picking/api-picking-plans/cancel', 'POST',
        {
            'id'            : planId,
            'cancel_reason' : ($('#cancel_reason').val())
        },
        true,
        MyPage.deleteSuccess
    );
}

/**
 * 出庫予定データ取消時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.deleteValidate = function(data) {
    // 取消可否検証
    if (!MyPage.validateEditCancel()) {
        return WNote.Form.validateResultSet('取消対象の予定はすでに未出庫以外の状態の為、取消を行うことはできません。');
    }
    if (MyPage.pickingKbn.current != MyPage.pickingKbn.new && !MyPage.validateInstockPlan()) {
        return WNote.Form.validateResultSet('取消対象の予定に対応する入庫予定がすでに未入庫以外の状態の為、取消を行うことはできません。');
    }

    var options = WNote.Util.Validate.ValidatorOptions();

    // cancel_reason
    options.rules['plan.cancel_reason']    = { required: true, maxlength: 2048 };
    options.messages['plan.cancel_reason'] = { required: '取消を行う場合は、取消理由を必ず入力してください。', maxlength: '取消理由は2048文字以内で入力してください。' };

    // 入力内容の検証
    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        return WNote.Form.validateResultSet('入力内容に不備があります。各入力内容をご確認ください。');
    }

    // 選択していないケース（※存在してはならないが・・）の検証
    if (!data || data == '') {
        return WNote.Form.validateResultSet('出庫予定が選択されていません。出庫予定を選択してください。');
    }

    return WNote.Form.validateResultSet(null);
}

/** ---------------------------------------------------------------------------
 *  検証メソッド
 *  -------------------------------------------------------------------------*/
/**
 * 編集、取消時に未出庫状態かどうかをチェックする
 *
 * @result {boolean} 検証結果（true: 未出庫状態（編集、取消可能）/false: 未出庫以外）
 */
MyPage.validateEditCancel = function() {
    result = WNote.ajaxValidateSend(
        '/api/picking/api-picking-plans/validate_request_edit',
        'POST',
        {
            'id': function(){
                return $('input[name="plan.id"]').val();
            }
        }
    );

    return (result && result.validate);
}

/**
 * 交換／修理の場合、編集、取消時に未入庫状態かどうかをチェックする
 *
 * @result {boolean} 検証結果（true: 未入庫状態（編集、取消可能）/false: 未入庫以外）
 */
MyPage.validateInstockPlan = function() {
    var instockPlanId = (MyPage.selectPlan.exchange.instock_plan) ? MyPage.selectPlan.exchange.instock_plan.id : null;
    instockPlanId = (instockPlanId) ? instockPlanId : MyPage.selectPlan.repair.instock_plan.id;

    result = WNote.ajaxValidateSend(
        '/api/instock/api-instock-plans/validate_instock_cancel',
        'POST',
        {
            'id': instockPlanId
        }
    );

    return (result && result.validate);
}

/**
 * 交換／修理の場合の資産管理番号とシリアル番号の相関チェック
 *
 * @result {boolean} 検証結果（true: 入力あり/false: 入力なし）
 */
MyPage.validateSerialAndAssetNo = function(element) {
    var prefix = $(element).attr('name').split('.')[0];
    var assetNo  = $.trim($('input[name="'+ prefix +'.asset_no"]').val());
    var serialNo = $.trim($('input[name="'+ prefix +'.serial_no"]').val());

    // いずれか一方の入力のみ許可
    if (assetNo == '' && serialNo == '') return true;
    if (assetNo != '' && serialNo != '') return true;

    return false;
}

/** ---------------------------------------------------------------------------
 *  Validator追加メソッド
 *  -------------------------------------------------------------------------*/
/**
 * 出庫区分が"交換"の場合に、入力された資産管理番号が在庫に存在するかどうかをチェックする
 *
 * @result {boolean} 検証結果（true: 未出庫状態（編集、取消可能）/false: 未出庫以外）
 */
$.validator.addMethod('validateAssetNo', function(value, element) {
    if (value == '') return true;

    result = WNote.ajaxValidateSend(
        '/api/asset/api-assets/validate_asset_no',
        'POST',
        {
            'asset_no': value
        }
    );

    return this.optional(element) || (result && result.validate);
}, '指定された資産管理番号が存在しません。');

/**
 * 出庫区分が"交換"の場合に、入力されたシリアル番号が在庫に存在するかどうかをチェックする
 *
 * @result {boolean} 検証結果（true: 在庫あり/false: 在庫なし）
 */
$.validator.addMethod('validateSerialNo', function(value, element) {
    if (value == '') return true;

    result = WNote.ajaxValidateSend(
        '/api/asset/api-assets/validate_serial_no',
        'POST',
        {
            'serial_no': value
        }
    );

    return this.optional(element) || (result && result.validate);
}, '指定されたシリアル番号が存在しません。');
