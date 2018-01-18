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
const MYPAGE_NEW = {
    FORM_KEY : 'form-instock-new',
    PREFIX   : 'new_',
    CONTENTS : '#wid-id-new',
    WIZARD   : {
        ALL  : '#new-instock-wizard',
        STEP1: '#wizard-plan-select',
        STEP2: '#wizard-serial-input',
        STEP3: '#wizard-fix'
    },
    SERIALS  : '#new_serial_list',
    SERIAL_INPUT: '#new_serial_input',
    COUNT_INPUT : '#new_input_instock_count',
    SERIAL_AREA : '.input-serial-area',
    COUNT_AREA  : '.input-count-area',
    ASSET_TYPE  : {
        ASSET : '1',
        COUNT : '2',
    }
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中の資産データ */
MyPage.New = {};
MyPage.New.selectNew = {
    plan  : {},
    detail: {}
};

/** datatableのインスタンス */
MyPage.New.planDatatable = null;
MyPage.New.detailDatatable = null;

/** タブクリック可否 */
MyPage.New.enbaleTabClick = false;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 入庫予定一覧テーブル（datatable）初期化
    MyPage.New.planDatatable = $('#plan-new-datatable').DataTable({
        paging    : false,
        scrollY   : 150,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'instock_kbn_name'    , width: '10%' },
            { data: 'plan_date'           , width: '10%' },
            { data: 'name'                , width: '24%' },
            { data: 'plan_count'          , width: '8%' },
            { data: 'instock_count'       , width: '8%' },
            { data: 'remarks'             , width: '40%' }
        ],
        data      : []
    });
    /** データテーブルイベント登録 */
    $('#plan-new-datatable tbody').on('click', 'tr', MyPage.New.selectedPlanHandler);

    // 入庫予定一覧テーブル（datatable）初期化
    MyPage.New.detailDatatable = $('#plan-new-detail-datatable').DataTable({
        paging    : false,
        scrollY   : 200,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'category_name'       , width: '12%' },
            { data: 'classification_name' , width: '15%' },
            { data: 'maker_name'          , width: '15%' },
            { data: 'product_name'        , width: '20%' },
            { data: 'model_name'          , width: '20%' },
            { data: 'plan_count'          , width: '8%' },
            { data: 'instock_count'       , width: '8%' }
        ],
        data      : []
    });
    /** データテーブルイベント登録 */
    $('#plan-new-detail-datatable tbody').on('click', 'tr', MyPage.New.selectedPlanDetailHandler);

    // Bootstrap Wizard 初期化
    $(MYPAGE_NEW.WIZARD.ALL).bootstrapWizard({
        'tabClass'   : 'wizard-menu',
        'onNext'     : MyPage.New.nextHandler,
        'onTabClick' : MyPage.New.clickTabHandler
    });
    /** 確定ボタンクリックイベント登録 */
    $(MYPAGE_NEW.WIZARD.ALL+' .finish').on('click', MyPage.New.fixHandler);

    /** シリアル入力テキストのEnterキー押下イベント／変更イベント登録 */
    $(MYPAGE_NEW.SERIAL_INPUT).on('keypress', function (e) { if(e.which === 13){ MyPage.New.inputSerialHandler(e); }});

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'serial-delete'     , MyPage.New.serialDelete);
    WNote.registerEvent('click', 'serial-delete-all' , MyPage.New.serialDeleteAll);

    /* 初期表示 */
    MyPage.New.clearProcess();
});

/** ---------------------------------------------------------------------------
 *  入庫ライブラリ（instocks.index.js）内宣言の実装
 *  -------------------------------------------------------------------------*/
/**
 * 新規入庫選択（入庫ライブラリ内の宣言の実装）
 */
MyPage.selectNew = function() {
    $(MYPAGE_NEW.CONTENTS).removeClass('hide');
}

/**
 * 新規入庫選択解除（入庫ライブラリ内の宣言の実装）
 */
MyPage.unselectNew = function() {
    $(MYPAGE_NEW.CONTENTS).addClass('hide');
}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示 - 入庫予定一覧）
 *  -------------------------------------------------------------------------*/
/**
 * 入庫予定一覧取得
 *
 */
MyPage.New.getPlans = function() {
    // 入庫予定一覧の取得
    var result = WNote.ajaxValidateSend('/api/instock/api-instock-plans/plans-instock', 'POST',　{});

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
MyPage.New.showPlans = function() {
    var result = MyPage.New.getPlans();
    MyPage.New.planDatatable
        .clear()
        .rows.add(result.plans)
        .draw();

    MyPage.New.detailDatatable
        .clear()
        .draw();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示 - 入庫予定詳細）
 *  -------------------------------------------------------------------------*/
/**
 * 入庫予定詳細一覧取得
 *
 * @param {integer} planId 入庫予定ID
 */
MyPage.New.getPlanDetails = function(planId) {
    // 入庫予定一覧の取得
    var result = WNote.ajaxValidateSend('/api/instock/api-instock-plan-details/details', 'POST',　{
        'plan_id': planId
    });

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、入庫予定詳細の一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 入庫予定詳細一覧表示
 *
 * @param {integer} detailId 入庫予定詳細ID
 */
MyPage.New.showPlanDetails = function(detailId) {
    var result = MyPage.New.getPlanDetails(detailId);
    if (result) {
        MyPage.New.detailDatatable
            .clear()
            .rows.add(result.details)
            .draw();
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（予定選択）
 *  -------------------------------------------------------------------------*/
/**
 * 入庫予定一覧選択
 */
MyPage.New.selectedPlanHandler = function() {
    var selected = MyPage.New.planDatatable.row( this ).data();
    if (selected) {
        MyPage.New.selectNew.plan = selected;
        MyPage.New.selectNew.detail = {};
        MyPage.New.showPlanDetails(MyPage.New.selectNew.plan.id);
        WNote.Util.All.highlightDataTableRow($(this), MyPage.New.planDatatable);
    }
}

/**
 * 入庫予定詳細一覧選択
 */
MyPage.New.selectedPlanDetailHandler = function() {
    var selected = MyPage.New.detailDatatable.row( this ).data();
    if (selected) {
        MyPage.New.selectNew.detail = selected;
        WNote.Util.All.highlightDataTableRow($(this), MyPage.New.detailDatatable);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（シリアル入力）
 *  -------------------------------------------------------------------------*/
/**
 * シリアル入力テキストでのEnterキー押下時のイベントハンドラー
 *
 * @param {object} event キー押下イベント
 */
MyPage.New.inputSerialHandler = function(event) {
    var value = $(event.target).val().trim();
    if (value == '') return;

    $(MYPAGE_NEW.SERIALS).append(function() {
        value = WNote.Util.All.zen2Han(value);
        if($(MYPAGE_NEW.SERIALS+' option[value="'+ value +'"]').length == 0) {
            return $("<option>").val(value).text(value);
        }
    });
    MyPage.New.setInputSerialCount();
    $(event.target).val('');
}

/**
 * シリアル一覧の選択値を削除する
 *
 * @param {object} event ボタンクリックイベント
 */
MyPage.New.serialDelete = function(event) {
    $(MYPAGE_NEW.SERIALS+' option:selected').each(function(i, e) {
        $(e).remove();
    });
    MyPage.New.setInputSerialCount();
}

/**
 * シリアル一覧の値をすべて削除する
 *
 * @param {object} event ボタンクリックイベント
 */
MyPage.New.serialDeleteAll = function(event) {
    if ($(MYPAGE_NEW.SERIALS+' option').length > 0) {
        WNote.showConfirmYesHandler = function() {
            MyPage.New.clearSerials();
        };
        WNote.showConfirmMessage('入力されているシリアルをすべてを削除してもよろしいですか？');
    }
}

/**
 * シリアル一覧をクリアする
 *
 * @param {object} event ボタンクリックイベント
 */
MyPage.New.clearSerials = function() {
    $(MYPAGE_NEW.SERIALS+' option').each(function(i, e) {
        $(e).remove();
    });
    MyPage.New.setInputSerialCount();
}

/**
 * シリアル一覧のデータを作成する
 *
 * @return {object} data Ajax送信データ
 */
MyPage.New.createSerialsData = function() {
    var data = { serials: [] };

    $(MYPAGE_NEW.SERIALS+' option').each(function(i, e) {
        data.serials.push($(e).val());
    });

    return data;
}

/**
 * シリアル一覧の件数を設定する
 *
 * @param {object} event ボタンクリックイベント
 */
MyPage.New.setInputSerialCount = function() {
    var count = $(MYPAGE_NEW.SERIALS+' option').length;
    $('#new_input_count').text(count);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（次へ進む）
 *  -------------------------------------------------------------------------*/
/**
 * タブクリック処理のハンドラー
 */
MyPage.New.clickTabHandler = function(tab, navigation, index) {
    return MyPage.New.enbaleTabClick;
}

/**
 * 「次へ進む」イベント処理のハンドラー
 */
MyPage.New.nextHandler = function(tab, navigation, index) {
    var validate = true;
    if (index == 1) { // 入庫予定選択
        validate = MyPage.New.nextStepPlan();
    }
    if (index == 2) { // 入庫シリアル入力
        validate = MyPage.New.nextStepSerial();
    }

    if (!validate) {
        return false;
    }

    // Smart Admin Style
    $(MYPAGE_NEW.WIZARD.ALL).find('.wizard-menu').children('ul').children('li').eq(index - 1).addClass('complete');
}

/**
 * 入庫内容確認／確定「入庫を確定する」イベント処理のハンドラー
 * 
 * @param {object} event イベント
 */
MyPage.New.fixHandler = function() {
    // 入庫データ保存
    MyPage.New.save();
}

/**
 * STEP1 入庫予定選択「次へ進む」イベント処理
 * 
 * @return {boolean} true:正常／false:エラー（次へ進まない）
 */
MyPage.New.nextStepPlan = function() {

    // 入庫予定選択検証
    if (!WNote.ajaxValidateWarning(null, MyPage.New.planValidator())) {
        return false;
    }

    if (MyPage.New.selectNew.detail.asset_type == MYPAGE_NEW.ASSET_TYPE.ASSET) { // 資産管理
        $(MYPAGE_NEW.SERIAL_AREA).removeClass('hide');
        $(MYPAGE_NEW.COUNT_AREA).addClass('hide');
        setTimeout(function() {
            $(MYPAGE_NEW.SERIAL_INPUT).focus();
        }, 100);
    } else { // 数量管理
        $(MYPAGE_NEW.SERIAL_AREA).addClass('hide');
        $(MYPAGE_NEW.COUNT_AREA).removeClass('hide');
        setTimeout(function() {
            $(MYPAGE_NEW.COUNT_INPUT).focus();
        }, 100);
    }

    return true;
}

/**
 * STEP2 入庫シリアル入力「次へ進む」イベント処理
 * 
 * @return {boolean} true:正常／false:エラー（次へ進まない）
 */
MyPage.New.nextStepSerial = function() {
    // 入庫予定選択検証
    if (!WNote.ajaxValidateWarning(null, MyPage.New.serialValidator())) {
        return false;
    }

    // 確認内容の表示
    MyPage.New.showFixDisplay();

    return true;
}

/** ---------------------------------------------------------------------------
 *  イベント処理（初期化・表示）
 *  -------------------------------------------------------------------------*/
/**
 * ステップ遷移を画面初期状態に戻す（※基本情報はクリアしない）
 *
 */
MyPage.New.clearProcess = function() {
    // 選択データを初期化
    MyPage.New.selectNew.plan   = {};
    MyPage.New.selectNew.detail = {};

    // シリアル入力内容をクリアする
    $(MYPAGE_NEW.SERIAL_INPUT).val('');
    MyPage.New.clearSerials();

    // 数量入力欄をクリアする
    $(MYPAGE_NEW.COUNT_INPUT).val('');

    // 確認画面の表示をクリアする
    MyPage.New.clearFixDisplay();

    // 初期ステップへ移動
    MyPage.New.enbaleTabClick = true;
    $(MYPAGE_NEW.WIZARD.ALL).find('a[href*="'+MYPAGE_NEW.WIZARD.STEP1+'"]').trigger('click');
    MyPage.New.enbaleTabClick = false;

    // Smart Admin Style 初期化
    $(MYPAGE_NEW.WIZARD.ALL).find('.wizard-menu').children('ul').children('li').removeClass('complete');

    // 入庫予定一覧を表示する
    MyPage.New.showPlans();
}

/**
 * 確認画面の表示をクリアする
 *
 */
MyPage.New.clearFixDisplay = function() {
    $('#fix_plan_date').text('');
    $('#fix_plan_name').text('');
    $('#fix_category').text('');
    $('#fix_classification').text('');
    $('#fix_maker').text('');
    $('#fix_product').text('');
    $('#fix_model').text('');
    $('#fix_plan_count').text('');
    $('#fix_instock_count').text('');
    $('#fix_support_limit_date').text('');
    $('#fix_input_count').text('');
    $('#fix_serials').text('');
}

/**
 * 確認画面に登録データを表示する
 *
 */
MyPage.New.showFixDisplay = function() {
    $('#fix_plan_date').text(MyPage.New.selectNew.plan.plan_date);
    $('#fix_plan_name').text(MyPage.New.selectNew.plan.name);
    $('#fix_category').text(MyPage.New.selectNew.detail.category_name);
    $('#fix_classification').text(MyPage.New.selectNew.detail.classification_name);
    $('#fix_maker').text(MyPage.New.selectNew.detail.maker_name);
    $('#fix_product').text(MyPage.New.selectNew.detail.product_name);
    $('#fix_model').text(MyPage.New.selectNew.detail.model_name);
    $('#fix_plan_count').text(MyPage.New.selectNew.detail.plan_count);
    $('#fix_instock_count').text(MyPage.New.selectNew.detail.instock_count);
    $('#fix_support_limit_date').text(MyPage.New.selectNew.detail.support_limit_date);

    var input_count = '0';
    if (MyPage.New.selectNew.detail.asset_type == MYPAGE_NEW.ASSET_TYPE.ASSET) {
        var serials = '';
        $(MYPAGE_NEW.SERIALS+' option').each(function(i, e) {
            serials += $(this).text() + '<br>';
            input_count++;
        });
    } else {
        input_count = $(MYPAGE_NEW.COUNT_INPUT).val();
    }

    $('#fix_input_count').text(input_count);
    $('#fix_serials').html(serials);
}


/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 入庫データを登録する
 *
 */
MyPage.New.save = function() {

    // 送信データ作成
    var data = MyPage.createSaveData();
    data.instock.instock_plan_id        = MyPage.New.selectNew.plan.id;
    data.instock.instock_plan_detail_id = MyPage.New.selectNew.detail.id;
    data.serials                        = MyPage.New.createSerialsData().serials;
    data.instock_count                  = $(MYPAGE_NEW.COUNT_INPUT).val();
    data.asset_id                       = MyPage.New.selectNew.detail.asset_id;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.New.fixValidator(data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '入力された内容の保存に失敗しました。再度保存してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/instock/api-instocks/add-new', 'POST',
        data,
        true,
        MyPage.New.saveSuccess
    );
}

/**
 * 保存ボタンクリック成功時のハンドラの実装（追加時）
 *
 * @param {object} data レスポンスデータ
 */
MyPage.New.saveSuccess = function(data) {
    WNote.ajaxSuccessHandler(data, '入庫情報をを登録しました。');
    MyPage.clearValidation();
    MyPage.New.clearProcess();
}

/** ---------------------------------------------------------------------------
 *  バリデータ処理
 *  -------------------------------------------------------------------------*/
/**
 * STEP1 入庫予定選択の「次へ進む」処理時のバリデータ
 */
MyPage.New.planValidator = function() {
    if (!MyPage.New.selectNew.plan.id) {
        return WNote.Form.validateResultSet('入庫予定が選択されていません。入庫予定を選択してください。');
    }

    if (!MyPage.New.selectNew.detail.id) {
        return WNote.Form.validateResultSet('入庫予定の詳細が選択されていません。入庫予定詳細を選択してください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}

/**
 * STEP2 入庫シリアル入力の「次へ進む」処理時のバリデータ
 */
MyPage.New.serialValidator = function() {

    if (MyPage.New.selectNew.detail.asset_type == MYPAGE_NEW.ASSET_TYPE.ASSET) {
        if ($(MYPAGE_NEW.SERIALS+' option').length < 1) {
           return WNote.Form.validateResultSet('入庫するシリアルが1件も入力されていません。シリアルを入力してください。');
        }
    } else {
        var count = Number.parseInt($(MYPAGE_NEW.COUNT_INPUT).val());
        if (isNaN(count)) {
           return WNote.Form.validateResultSet('入庫数量が入力されていないか、数値で入力されていません。');
        }
        if (count < 1) {
           return WNote.Form.validateResultSet('入庫数量を1以上で入力してください。');
        }
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}

/**
 * STEP3 入庫内容確認／確定の「次へ進む」処理時のバリデータ
 *
 * @param {object} data 送信データ
 */
MyPage.New.fixValidator = function(data) {

    var validate = MyPage.saveValidator(data);
    if (!validate.result) {
        return validate;
    }

    var validate = MyPage.New.planValidator();
    if (!validate.result) {
        return validate;
    }

    var validate = MyPage.New.serialValidator();
    if (!validate.result) {
        return validate;
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}
