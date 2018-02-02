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
    FORM_KEY: "form-cpu"
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中のCPUデータ */
MyPage.selectCpu = {
    cpu: {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {
    // データテーブル初期化
    var responsiveHelper = undefined;
    var breakpointDefinition = { tablet: 1024, phone: 480 }
    var dtable = $('#side-datatable-cpu').DataTable({
        "iDisplayLength": 15,
        "order": [],
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-12'>>"+
            "t"+
            "<'dt-toolbar-footer'<'col-xs-12 col-sm-4 hidden-xs'i><'col-xs-12 col-sm-8'p>>",
        "autoWidth" : true,
        "preDrawCallback" : function() {
            if (!responsiveHelper) {
                responsiveHelper = new ResponsiveDatatablesHelper($('#side-datatable-cpu'), breakpointDefinition);
            }
        },
        "rowCallback" : function(nRow) {
            responsiveHelper.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
            responsiveHelper.respond();
        },
        "fnInfoCallback": function( oSettings, iStart, iEnd, iMax, iTotal, sPre ) {
            return iStart + "-" + iEnd + " / " + iTotal;
        }
    });
    $("#side-datatable-cpu thead th input[type=text]").on( 'keyup change', function () {
        dtable
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    });

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'edit'   , MyPage.edit);
    WNote.registerEvent('click', 'add'    , MyPage.add);
    WNote.registerEvent('click', 'cancel' , MyPage.cancel);
    WNote.registerEvent('click', 'save'   , MyPage.save);
    WNote.registerEvent('click', 'delete' , MyPage.delete);
    WNote.registerEvent('click', 'side-datatable-cpu' , WNote.sideDatatableClickHandler); // サイドデータテーブル（Element/Parts/side-datatable-cpu）用クリックイベント
});

/** ---------------------------------------------------------------------------
 *  イベント処理（CPU選択）
 *  -------------------------------------------------------------------------*/
/**
 * サイドリスト（Element/Parts/side-datatable）用クリックイベントのハンドラの実装
 */
WNote.sideDatatableClickHandler = function(event) {
    // 選択CPUの取得
    var id = $(event.target).attr(WNOTE.DATA_ATTR.ID);

    // 文字の部分選択時はSPANタグ要素がevent.targetになっているので親要素よりIDを取得する
    if ($(event.target).prop("tagName") == "SPAN") {
        id = $(event.target).parent().attr(WNOTE.DATA_ATTR.ID);
    }

    // CPUの取得
    MyPage.getCpu(id);
}

/**
 * CPU取得
 *
 * @param {integer} cpu_id CPUID
 */
MyPage.getCpu = function(cpu_id) {
    // CPUデータの取得
    WNote.ajaxFailureMessage = 'CPUデータの読込に失敗しました。再度CPUを選択してください。';
    WNote.ajaxSendBasic('/api/master/general/api-cpus/cpu', 'POST',
        { 'cpu_id': cpu_id },
        true,
        MyPage.getCpuSuccess
    );
}

/**
 * CPU取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.getCpuSuccess = function(data) {
    MyPage.selectCpu.cpu = data.data.param.cpu;
    WNote.Form.viewMode(MYPAGE.FORM_KEY);
    WNote.hideLoading();
    MyPage.setFormValues();
}

/**
 * CPUデータをフォームに表示する
 * 
 */
MyPage.setFormValues = function() {
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.setFormValues(MyPage.selectCpu.cpu);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（編集画面表示）
 *  -------------------------------------------------------------------------*/
/**
 * 編集ボタンクリックイベントのハンドラの実装
 */
MyPage.edit = function() {
    WNote.Form.editMode(MYPAGE.FORM_KEY);
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
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 保存ボタンクリックイベントのハンドラの実装
 */
MyPage.save = function() {
    var saveUrl  = '/api/master/general/api-cpus/';
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
    MyPage.getCpu(data.data.param.cpu.id);
    WNote.ajaxSuccessHandler(data, '入力された内容を登録しました。ページを再表示します。');
    location.reload();
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
}

/**
 * CPUデータ保存時の検証を行う
 *
 * @param {string} saveType 保存タイプ（add or edit）
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.saveValidate = function(saveType, data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // kanme 
    options.rules['cpu.kname']    = { required: true, maxlength: 30 };
    options.messages['cpu.kname'] = { required: '表示名を入力してください。', maxlength: '表示名は最大30文字で入力してください。' };

    // name
    options.rules['cpu.name']    = { required: true, maxlength: 80 };
    options.messages['cpu.name'] = { required: 'CPU名（正式名）を入力してください。', maxlength: 'CPU名（正式名）は最大80文字で入力してください。' };

    // maker_id
    options.rules['cpu.maker_id']    = { required: true };
    options.messages['cpu.maker_id'] = { required: '製造元を選択してください。' };

    // psts
    options.rules['cpu.psts']    = { required: true };
    options.messages['cpu.psts'] = { required: '製品ステータスを選択してください。' };

    // sales_start
    options.rules['cpu.sales_start']    = { date: true };
    options.messages['cpu.sales_start'] = { date: '有効な販売開始日をyyyy/mm/ddの形式で入力してください。' };

    // sales_start
    options.rules['cpu.sales_end']    = { date: true };
    options.messages['cpu.sales_end'] = { date: '有効な販売終了日をyyyy/mm/ddの形式で入力してください。' };

    // remarks
    options.rules['cpu.remarks']    = { maxlength: 512 };
    options.messages['cpu.remarks'] = { maxlength: '備考は最大512文字で入力してください。' };

    // dsts
    options.rules['cpu.dsts']    = { required: true };
    options.messages['cpu.dsts'] = { required: '使用中／停止を選択してください。' };

    // 入力内容の検証
    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        return WNote.Form.validateResultSet('入力内容に不備があります。各入力内容をご確認ください。');
    }

    // 選択していないケース（※存在してはならないが・・）の検証
    if (saveType == "edit" && (!data || data == '')) {
        return WNote.Form.validateResultSet('CPUが選択されていません。CPUを選択してください。');
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
    WNote.showConfirmMessage('CPUを削除するとCPUに関連するデータがすべて削除され、復元することができません。本当に削除してもよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.deleteCpu;
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
    WNote.ajaxSuccessHandler(data, '選択されたデータが正常に削除されました。ページを再表示します。');
    location.reload();
}

/**
 * CPUデータを削除する
 *
 */
MyPage.deleteCpu = function() {
    // 送信データ作成
    var cpu_id = (MyPage.selectCpu.cpu['id']) ? MyPage.selectCpu.cpu['id'] : undefined;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(cpu_id, MyPage.deleteValidate(cpu_id))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = 'CPUデータの削除に失敗しました。再度削除してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/master/general/api-cpus/delete', 'POST',
        { 'id': cpu_id },
        true,
        MyPage.deleteSuccess
    );
}

/**
 * CPUデータ削除時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.deleteValidate = function(data) {
    var message;

    if (!data || data == '') {
        message = 'CPUが選択されていません。CPUを選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}
