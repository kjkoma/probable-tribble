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
const MYPAGE_ENTRY = {
    FORM_KEY: 'form-entry',
    PREFIX  : 'entry_'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
MyPage.Entry = {};

/** フォーム操作用 */
MyPage.Entry.form;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // フォーム操作用インスタンスの作成
    MyPage.Entry.form = new WNote.Lib.Form({});

    /** Widget表示イベント追加 */
    MyPage.showEntryWidgetRegister('EntryWidget', MyPage.Entry.showWidget);

    /** select2 登録 */
    WNote.Select2.sUser('#entry_work_suser_id'             , null                      , false, '作業者選択');
    WNote.Select2.classification('#entry_classification_id', null                      , true,  '分類（数量品出庫時）');
    WNote.Select2.product('#entry_product_id'              , '#entry_classification_id', true,  '製品（数量品出庫時）');
    WNote.Select2.model('#entry_product_model_id'          , '#entry_product_id'       , true,  'モデル（数量品出庫時）');

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'save-entry'   , MyPage.Entry.save);
    WNote.registerEvent('click', 'add-picking'  , MyPage.Entry.picking);
});

/** ---------------------------------------------------------------------------
 *  イベント処理（出庫予定詳細入力Widget表示）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定詳細入力Widget表示処理
 * 
 */
MyPage.Entry.showWidget = function() {
    WNote.Form.clearFormValues(MYPAGE_ENTRY.FORM_KEY);
    MyPage.Entry.form.validateClear();
    MyPage.Entry.setFormVlaues();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫予定詳細入力フォーム表示処理
 * 
 */
MyPage.Entry.setFormVlaues = function() {
     // 出庫登録ボタンを非表示
     WNote.Util.addClassByDataAttr('entry-picking-actions', 'hidden');
    if (MyPage.selectData.selected.plan_sts == $('#plan_sts_not').val()) {
        // 未出庫の場合はフォーム値設定がない為、設定処理は不要
        return ;
    }
    if (MyPage.selectData.selected.plan_sts == $('#plan_sts_work').val()) {
        // 出庫準備のみ出庫登録ボタンを表示
        WNote.Util.removeClassByDataAttr('entry-picking-actions', 'hidden');
    }

    var data = {};
    data.plan_date            = MyPage.selectData.selected.plan_date;
    data.work_suser_id        = MyPage.selectData.selected.picking_plan.work_suser_id;
    data.work_suser_text      = MyPage.selectData.selected.work_suser_name;
    data.name                 = MyPage.selectData.selected.picking_plan.name;
    data.remarks              = MyPage.selectData.selected.picking_plan.remarks;
    data.classification_id    = MyPage.selectData.selected.classification_id;
    data.classification_text  = MyPage.selectData.selected.classification.kname;
    data.product_id           = MyPage.selectData.selected.product_id;
    data.product_text         = MyPage.selectData.selected.product.kname;
    data.product_model_id     = MyPage.selectData.selected.product_model_id;
    data.product_model_text   = (MyPage.selectData.selected.product_model) ? MyPage.selectData.selected.product_model.kname : '';

    data = (MyPage.selectData.selected.asset_type == $('#asset_type_asset').val()) ? MyPage.Entry.setFormTypeAsset(data) : MyPage.Entry.setFormTypeCount(data);

    WNote.Form.setFormValuesWithPrefix(data, MYPAGE_ENTRY.PREFIX);
}

/**
 * 資産タイプが資産の場合の入力・表示制御
 * 
 * @param {object} data 表示用データオブジェクト
 */
MyPage.Entry.setFormTypeAsset = function(data) {
    data.serial_no            = MyPage.selectData.selected.serial_no;
    data.asset_no             = MyPage.selectData.selected.asset.asset_no;
    data.asset_remarks        = MyPage.selectData.selected.asset.remarks;
    $('#entry_serial_no').prop('disabled', false);
    $('#entry_asset_no').prop('disabled', false);
    $('#entry_asset_remarks').prop('disabled', false);

    return data;
}

/**
 * 資産タイプが資産の場合の入力・表示制御
 * 
 * @param {object} data 表示用データオブジェクト
 */
MyPage.Entry.setFormTypeCount = function(data) {
    data.serial_no            = '';
    data.asset_no             = '';
    data.asset_remarks        = '';
    $('#entry_serial_no').prop('disabled', true);
    $('#entry_asset_no').prop('disabled', true);
    $('#entry_asset_remarks').prop('disabled', true);

    return data;
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリックイベントのハンドラの実装
 */
MyPage.Entry.save = function() {
    var saveUrl  = '/api/picking/api-picking-plan-details/edit';

    // 送信データ作成
    var data = WNote.Form.createAjaxData(MYPAGE_ENTRY.FORM_KEY);
    data.entry.plan_id        = MyPage.selectData.selected.id;
    data.entry.plan_detail_id = MyPage.selectData.selected.plan_detail_id;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.Entry.saveValidate(data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '入力された内容の保存に失敗しました。再度保存してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic(saveUrl, 'POST',
        data,
        true,
        MyPage.Entry.saveEditSuccess
    );
}

/**
 * 保存ボタンクリック成功時のハンドラの実装（編集時）
 *
 * @param {object} data レスポンスデータ
 */
MyPage.Entry.saveEditSuccess = function(data) {
WNote.log(data); // debug
    MyPage.Entry.form.validateClear();
    WNote.ajaxSuccessHandler(data, '入力された内容を保存しました。');
    MyPage.reSelectedRow(data.data.param.plan);
}

/**
 * 出庫予定詳細データ保存時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.Entry.saveValidate = function(data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // plan_date
    options.rules['entry.plan_date']    = { required: true, date: true, dateFormat: true };
    options.messages['entry.plan_date'] = { required: '出庫予定日を入力してください。', date: '有効な出庫予定日をyyyy/mm/ddの形式で入力してください。', dateFormat: '出庫予定日はyyyy/mm/ddの形式で入力してください。' };

    // work_suser_id
    options.rules['entry.work_suser_id']    = { required: true };
    options.messages['entry.work_suser_id'] = { required: '作業者を入力してください。' };

    // description
    options.rules['entry.description']    = { maxlength: 60 };
    options.messages['entry.description'] = { maxlength: '出庫件名は最大60文字で入力してください。' };

    // product_id
    options.rules['entry.product_id']    = { required: true };
    options.messages['entry.product_id'] = { required: '製品を選択してください。' };

    // serial
    options.rules['entry.serial_no']    = { maxlength: 120, validateSerialNo: true };
    options.messages['entry.serial_no'] = { maxlength: 'シリアル番号は最大120文字で入力してください。' };

    MyPage.Entry.form.validator = $('#' + MYPAGE_ENTRY.FORM_KEY).validate(options);
    MyPage.Entry.form.validator.form();
    if (!MyPage.Entry.form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（出庫登録）
 *  -------------------------------------------------------------------------*/
/**
 * 出庫登録ボタンクリックイベントのハンドラの実装
 */
MyPage.Entry.picking = function() {
    WNote.showConfirmMessage('出庫登録を行うと取消ができません。また、入力内容を保存していない場合は反映されません。出庫登録を行ってよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.Entry.addPicking;
}
/**
 * 出庫登録ボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.addPickingSuccess = function(data) {
WNote.log(data); // debug
    WNote.ajaxSuccessHandler(data, '選択されたデータが正常に出庫登録されました。検索結果を再表示します。');
    MyPage.reShowPlans();
}

/**
 * 指定された出庫予定データを出庫登録（ステータスを出庫前に変更）
 *
 */
MyPage.Entry.addPicking = function() {
    // データ保存
    WNote.ajaxFailureMessage = '出庫登録に失敗しました。。再度出庫登録してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/picking/api-picking-plan-details/add-picking', 'POST',
        {
            'id' : MyPage.selectData.selected.plan_detail_id
        },
        true,
        MyPage.addPickingSuccess
    );
}

/** ---------------------------------------------------------------------------
 *  Validator追加メソッド
 *  -------------------------------------------------------------------------*/
/**
 * 入力シリアルの資産・在庫チェックの追加
 *
 */
$.validator.addMethod('validateSerialNo', function(value, element) {
    result = WNote.ajaxValidateSend(
        '/api/stock/api-stocks/validate_serial_no',
        'POST',
        {
            'serial_no': function(){
                return $('input[name="entry.serial_no"]').val();
            }
        }
    );

    return this.optional(element) || (result && result.validate);
}, '入力されたシリアル番号の資産・在庫はありません。');

