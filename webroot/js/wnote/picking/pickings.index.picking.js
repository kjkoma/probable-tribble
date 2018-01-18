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
const MYPAGE_PICKING = {
    FORM_KEY     : 'form-input',
    SERIAL_INPUT : '#serial_no',
    INPUT_SECTION: '#serial-input-section',
    LIST_ROW     : '#list-grid-row'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中の資産データ */
MyPage.Picking = {};
MyPage.Picking.selectData = {
    detail: {}
};

/** datatableのインスタンス */
MyPage.Picking.datatable = null;

/** フォーム操作用 */
MyPage.Picking.form;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // フォーム操作用インスタンスの作成
    MyPage.Picking.form = new WNote.Lib.Form({});

    // 出庫予定一覧テーブル（datatable）初期化
    MyPage.Picking.datatable = $('#plans-datatable').DataTable({
        paging    : false,
        scrollY   : 200,
        scrollX   : false,
        searching : true,
        ordering  : false,
        language  : {
            search : '一覧内フィルタ　:　'
        },
        columns   : [
            { data: 'picking_kbn_name'    , width: '8%' },
            { data: 'plan_date'           , width: '8%' },
            { data: 'req_date'            , width: '8%' },
            { data: 'req_user_name'       , width: '8%' },
            { data: 'dlv_user_name'       , width: '8%' },
            { data: 'classification_name' , width: '12%' },
            { data: 'product_name'        , width: '16%' },
            { data: 'product_model_name'  , width: '16%' },
            { data: 'serial_no'           , width: '8%' },
            { data: 'asset_no'            , width: '8%' }
        ],
        data      : []
    });
    /** データテーブルイベント登録 */
    $('#plans-datatable tbody').on('click', 'tr', MyPage.Picking.selectedPlanHandler);

    /** シリアル入力テキストのEnterキー押下イベント／変更イベント登録 */
    $(MYPAGE_PICKING.SERIAL_INPUT).on('keypress', function (e) { if(e.which === 13){ MyPage.Picking.inputSerialHandler(e); }});

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'fix-picking' , MyPage.Picking.save);

    // 一覧のウィジットを非表示
    $(MYPAGE_PICKING.LIST_ROW).addClass('hidden');
});

/** ---------------------------------------------------------------------------
 *  出庫ライブラリ（pickings.index.js）内宣言の実装
 *  -------------------------------------------------------------------------*/
/**
 * シリアル出庫選択（出庫ライブラリ内の宣言の実装）
 */
MyPage.selectSerial = function() {
    $(MYPAGE_PICKING.INPUT_SECTION).removeClass('hidden');
}

/**
 * シリアル出庫選択解除（出庫ライブラリ内の宣言の実装）
 */
MyPage.unselectSerial = function() {
    $(MYPAGE_PICKING.INPUT_SECTION).addClass('hidden');
    $(MYPAGE_PICKING.SERIAL_INPUT).val('');
    MyPage.Picking.clearSelected();
}

/**
 * 一覧選択出庫選択（出庫ライブラリ内の宣言の実装）
 */
MyPage.selectAsset = function() {
    $(MYPAGE_PICKING.LIST_ROW).removeClass('hidden');
    MyPage.Picking.showPlans();
}

/**
 * 一覧選択出庫選択解除（出庫ライブラリ内の宣言の実装）
 */
MyPage.unselectAsset = function() {
    $(MYPAGE_PICKING.LIST_ROW).addClass('hidden');
    MyPage.Picking.clearSelected();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（出庫予定一覧取得）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定一覧を取得する
 *
 */
MyPage.Picking.getPlans = function() {
    // 出庫予定一覧の取得
    var result = WNote.ajaxValidateSend('/api/picking/api-picking-plan-details/plans-picking', 'POST',　{});

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
MyPage.Picking.showPlans = function() {
    MyPage.Picking.clearSelected();

    var result = MyPage.Picking.getPlans();
    if (result) {
        MyPage.Picking.datatable
            .clear()
            .rows.add(result.plans)
            .draw();
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（出庫予定詳細取得）
 *  -------------------------------------------------------------------------*/
/**
 * 指定されたシリアルに対応する出庫予定詳細を取得する
 *
 * @param {string} serialNo シリアル番号
 */
MyPage.Picking.getPlanDetail = function(serialNo) {
    var data = WNote.ajaxValidateSend(
        '/api/picking/api-picking-plan-details/get_enable_picking_plan',
        'POST',
        {
            'serial_no': $(MYPAGE_PICKING.SERIAL_INPUT).val()
        }
    );

    if (!data || !data.detail) {
        WNote.showErrorMessage('指定されたシリアルに対応する出庫情報が取得できません。');
    }

    return data.detail;
}

/** ---------------------------------------------------------------------------
 *  イベント処理（シリアル入力）
 *  -------------------------------------------------------------------------*/
/**
 * シリアル入力テキストでのEnterキー押下時のイベントハンドラー
 *
 * @param {object} event キー押下イベント
 */
MyPage.Picking.inputSerialHandler = function(event) {
    var value = $(event.target).val().trim();
    if (value == '') return;

    // 入力シリアル番号の検証
    if (!WNote.ajaxValidateWarning([], MyPage.Picking.saveValidate())) {
        MyPage.Picking.clearSelected();
        return;
    }

    // 入力シリアル番号の出庫予定詳細を取得
    MyPage.Picking.selectData.detail = MyPage.Picking.getPlanDetail();

    // 出庫情報・資産情報を表示
    WNote.Picking.Plan.showWidget(MyPage.Picking.selectData.detail.id);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（予定選択）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定一覧の行選択時のイベントハンドラ
 *
 * @param {object} event キー押下イベント
 */
MyPage.Picking.selectedPlanHandler = function() {
    var selected = MyPage.Picking.datatable.row( this ).data();
    if (selected) {
        MyPage.Picking.selectData.detail = selected;
        WNote.Util.All.highlightDataTableRow($(this), MyPage.Picking.datatable);
    }

    // 出庫情報・資産情報を表示
    WNote.Picking.Plan.showWidget(MyPage.Picking.selectData.detail.id);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（選択解除）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定／シリアルの選択状態を解除する
 *
 */
MyPage.Picking.clearSelected = function() {
    MyPage.Picking.selectData.detail = {};
    WNote.Picking.Plan.hideWidget();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 入庫データを登録する
 *
 */
MyPage.Picking.save = function() {

    // 送信データ作成
    var data  = MyPage.createSaveData();
    data.plan_detail_id = MyPage.Picking.selectData.detail.id;

    // 基本入力の検証
    MyPage.Picking.form.validateClear();
    if (!WNote.ajaxValidateWarning(data, MyPage.saveValidator(data))) {
        return;
    }
    // シリアル入力時の検証
    if (MyPage.selectType.serial &&
        !WNote.ajaxValidateWarning(data, MyPage.Picking.saveValidate(data))) {
        return;
    }
    // 一覧選択時の検証
    if (MyPage.selectType.asset &&
        !MyPage.Picking.selectData.detail.id) {
        WNote.ajaxValidateWarning([], WNote.Form.validateResultSet('出庫対象が選択されていません。出庫対象を選択してください。'));
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '出庫に失敗しました。再度出庫してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/picking/api-pickings/add', 'POST',
        data,
        true,
        MyPage.Picking.saveSuccess
    );
}

/**
 * 保存ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.Picking.saveSuccess = function(data) {
    WNote.ajaxSuccessHandler(data, '指定在庫を出荷に更新しました。');
    MyPage.clearValidation();
    MyPage.Picking.form.validateClear();
    WNote.Picking.Plan.hideWidget();
    if (MyPage.selectType.serial) {
        $(MYPAGE_PICKING.SERIAL_INPUT).val('');
    } else {
        MyPage.Picking.showPlans();
    }
}

/**
 * 出庫確定時、および、出庫情報表示時の検証を行う
 *
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.Picking.saveValidate = function() {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // serial_no
    options.rules['input.serial_no']    = { required: true, maxlength: 120, validateSerialNo: true };
    options.messages['input.serial_no'] = { required: 'シリアル番号を入力してください。', maxlength: 'シリアル番号は最大120文字で入力してください。' };

    MyPage.Picking.form.validator = $('#' + MYPAGE_PICKING.FORM_KEY).validate(options);
    MyPage.Picking.form.validator.form();
    if (!MyPage.Picking.form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  Validator追加メソッド
 *  -------------------------------------------------------------------------*/
/**
 * 入力シリアルの出庫予定チェックの追加
 *
 */
$.validator.addMethod('validateSerialNo', function(value, element) {
    result = WNote.ajaxValidateSend(
        '/api/picking/api-picking-plan-details/validate_enable_picking_plan',
        'POST',
        {
            'serial_no': function(){
                return $(MYPAGE_PICKING.SERIAL_INPUT).val();
            }
        }
    );

    return this.optional(element) || (result && result.validate);
}, '入力されたシリアル番号の出庫予定はありません。');
