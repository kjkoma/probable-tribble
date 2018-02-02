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
    FORM_KEY: "form-stocktake"
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中の棚卸データ */
MyPage.selectData = {
    stocktake: {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    /** select2 登録 */
    WNote.Select2.sUser('#stocktake_suser_id', null, true, '棚卸担当者選択');
    WNote.Select2.sUser('#confirm_suser_id'  , null, true, '棚卸確認者選択');

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'edit'   , MyPage.edit);
    WNote.registerEvent('click', 'add'    , MyPage.add);
    WNote.registerEvent('click', 'cancel' , MyPage.cancel);
    WNote.registerEvent('click', 'save'   , MyPage.save);
    WNote.registerEvent('click', 'delete' , MyPage.delete);

    WNote.registerEvent('click', 'fix-stock'     , MyPage.fixStockHandler);
    WNote.registerEvent('click', 'fix-stocktake' , MyPage.fixStocktakeHandler);

});

/** ---------------------------------------------------------------------------
 *  イベント処理（棚卸選択）
 *  -------------------------------------------------------------------------*/
/**
 * サイドリスト（Element/Parts/side-list）用クリックイベントのハンドラの実装
 */
WNote.sideListClickHandler = function(event) {
    // 選択棚卸の取得
    var id = $(event.target).attr(WNOTE.DATA_ATTR.ID);

    // 文字の部分選択時はSPANタグ要素がevent.targetになっているので親要素よりIDを取得する
    if ($(event.target).prop("tagName") == "SPAN") {
        id = $(event.target).parent().attr(WNOTE.DATA_ATTR.ID);
    }

    // 選択行ハイライト
    WNote.Util.All.highlightNestableListRow(event.target);

    // 棚卸の取得
    MyPage.getStocktake(id);
}

/**
 * 棚卸取得
 *
 * @param {integer} stocktakeId 棚卸ID
 */
MyPage.getStocktake = function(stocktakeId) {
    // 棚卸データの取得
    WNote.ajaxFailureMessage = '棚卸データの読込に失敗しました。再度棚卸を選択してください。';
    WNote.ajaxSendBasic('/api/stocktake/api-stocktakes/stocktake', 'POST',
        { 'stocktake_id': stocktakeId },
        true,
        MyPage.getStocktakeSuccess
    );
}

/**
 * 棚卸取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.getStocktakeSuccess = function(data) {
    MyPage.selectData.stocktake = data.data.param.stocktake;
    MyPage.setFormValues();
    WNote.Form.viewMode(MYPAGE.FORM_KEY);
    WNote.hideLoading();
    WNote.Stocktake.showWidget(MyPage.selectData.stocktake.id);
}

/**
 * 棚卸データをフォームに表示する
 * 
 */
MyPage.setFormValues = function() {
    WNote.Form.clearFormValues(MYPAGE.FORM_KEY);
    WNote.Form.setFormValues(MyPage.selectData.stocktake);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（モード変更時拡張処理）
 *  -------------------------------------------------------------------------*/
/**
 * 追加モード変更時
 * 
 */
WNote.Form.addModeExtend = function() {
    MyPage.showFixActions(false);
    WNote.Stocktake.hideWidget();
}

/**
 * 編集モード変更時
 * 
 */
WNote.Form.editModeExtend = function() {
    MyPage.showFixActions(false);
    WNote.Stocktake.hideWidget();
}

/**
 * 表示モード変更時
 * 
 */
WNote.Form.viewModeExtend = function() {
    MyPage.showFixActions(true);
    WNote.Stocktake.showWidget(MyPage.selectData.stocktake.id);
}

/**
 * 初期モード変更時
 * 
 */
WNote.Form.initModeExtend = function() {
    WNote.Util.All.unHighlightNestableListRow();
    MyPage.showFixActions(false);
    WNote.Stocktake.hideWidget();
}

/**
 * 確定ボタン表示／非表示切り替え
 * 
 * @param {boolean} show true:表示／false:非表示
 */
MyPage.showFixActions = function(show) {
    var func = (show) ? WNote.Util.removeClassByDataAttr : WNote.Util.addClassByDataAttr;
    func('fix-actions', 'hidden');
}

/**
 * 棚卸確定ボタン表示／非表示切り替え
 * 
 * @param {boolean} show true:表示／false:非表示
 */
MyPage.showFixStocktake = function(show) {
    var func = (show) ? WNote.Util.removeClassByDataAttr : WNote.Util.addClassByDataAttr;
    func('fix-stocktake', 'hidden');
}

/**
 * 在庫確定ボタン表示／非表示切り替え
 * 
 * @param {boolean} show true:表示／false:非表示
 */
MyPage.showFixStock = function(show) {
    var func = (show) ? WNote.Util.removeClassByDataAttr : WNote.Util.addClassByDataAttr;
    func('fix-stock', 'hidden');
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
    var saveUrl  = '/api/stocktake/api-stocktakes/';
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
    MyPage.getStocktake(data.data.param.stocktake.id);
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
 * 棚卸データ保存時の検証を行う
 *
 * @param {string} saveType 保存タイプ（add or edit）
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.saveValidate = function(saveType, data) {
    var message;
    var options = WNote.Util.Validate.ValidatorOptions();

    // stocktake_date
    options.rules['stocktake.stocktake_date']    = { required: true, date: true };
    options.messages['stocktake.stocktake_date'] = { required: '棚卸日を入力してください。', date: '有効な棚卸日をyyyy/mm/ddの形式で入力してください。' };

    // req_user_id
    options.rules['stocktake.stocktake_suser_id']    = { required: true };
    options.messages['stocktake.stocktake_suser_id'] = { required: '棚卸担当者を選択してください。'};

    // remarks
    options.rules['stocktake.remarks']    = { maxlength: 4096 };
    options.messages['stocktake.remarks'] = { maxlength: '備考は最大4096文字で入力してください。' };

    WNote.Form.validator = $('#' + MYPAGE.FORM_KEY).validate(options);
    WNote.Form.validator.form();
    if (!WNote.Form.validator.valid()) {
        message = '入力内容に不備があります。各入力内容をご確認ください。';
    }

    if (saveType == "edit" && (!data || data == '')) {
        message = '棚卸が選択されていません。棚卸を選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（削除）
 *  -------------------------------------------------------------------------*/
/**
 * 削除ボタンクリックイベントのハンドラの実装
 */
MyPage.delete = function() {
    WNote.showConfirmMessage('棚卸データを削除すると棚卸データに関連するデータがすべて削除され、復元することができません。本当に削除してもよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.deleteStocktake;
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
 * 棚卸データを削除する
 *
 */
MyPage.deleteStocktake = function() {
    // 送信データ作成
    var stocktakeId = (MyPage.selectData.stocktake.id) ? MyPage.selectData.stocktake.id : undefined;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(stocktakeId, MyPage.deleteValidate(stocktakeId))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '棚卸データの削除に失敗しました。再度削除してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/stocktake/api-stocktakes/delete', 'POST',
        { 'id': stocktakeId },
        true,
        MyPage.deleteSuccess
    );
}

/**
 * 棚卸データ削除時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.deleteValidate = function(data) {
    var message;

    if (!data || data == '') {
        message = '棚卸が選択されていません。棚卸を選択してください。';
    }

    return WNote.Form.validateResultSet(message);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（在庫を締める）
 *  -------------------------------------------------------------------------*/
/**
 * 在庫を締めるボタンクリックイベントのハンドラの実装
 */
MyPage.fixStockHandler = function() {
    WNote.showConfirmMessage('現時点の在庫状態で棚卸対象在庫を確定しますがよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.fixStock;
}
/**
 * 在庫を締めるボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.fixStockSuccess = function(data) {
WNote.log(data); // debug
    WNote.ajaxSuccessHandler(data, '現時点の在庫状態で棚卸対象在庫を確定しました。');
    WNote.Stocktake.selectSummary();
}

/**
 * 現時点で在庫を締める
 *
 */
MyPage.fixStock = function() {
    // 送信データ作成
    var stocktakeId = (MyPage.selectData.stocktake.id) ? MyPage.selectData.stocktake.id : undefined;

    // データ保存
    WNote.ajaxFailureMessage = '在庫締め処理に失敗しました。再度実行してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/stocktake/api-stocktakes/fixStock', 'POST',
        { 'id': stocktakeId },
        true,
        MyPage.fixStockSuccess
    );
}

/** ---------------------------------------------------------------------------
 *  イベント処理（棚卸を確定する）
 *  -------------------------------------------------------------------------*/
/**
 * 棚卸を確定するボタンクリックイベントのハンドラの実装
 */
MyPage.fixStocktakeHandler = function() {
    WNote.showConfirmMessage('現時点の棚卸結果で棚卸を確定しますがよろしいですか？ 確定後は棚卸を行うことは出来なくなりますのでご注意ください。');
    WNote.showConfirmYesHandler = MyPage.fixStocktake;
}

/**
 * 棚卸を確定するボタンクリック成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.fixStocktakeSuccess = function(data) {
WNote.log(data); // debug
    WNote.ajaxSuccessHandler(data, '現時点の棚卸結果で棚卸を確定しました。ページを再表示します。');
    location.reload();
}

/**
 * 現時点で在庫を締める
 *
 */
MyPage.fixStocktake = function() {
    // 送信データ作成
    var stocktakeId = (MyPage.selectData.stocktake.id) ? MyPage.selectData.stocktake.id : undefined;

    // データ保存
    WNote.ajaxFailureMessage = '棚卸確定処理に失敗しました。再度実行してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/stocktake/api-stocktakes/fixStocktake', 'POST',
        { 'id': stocktakeId },
        true,
        MyPage.fixStocktakeSuccess
    );
}
