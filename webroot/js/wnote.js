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
const WNOTE = {
    // フォーム表示ステータス
    FORM_STATUS : {
        INIT   : "init",
        ADD    : "add",
        EDIT   : "edit",
        VIEW   : "view"
    },
    // 操作アクション
    ACTION : {
        ADD    : "add",
        EDIT   : "edit",
        CANCEL : "cancel",
        DELETE : "delete",
        SAVE   : "save"
    },
    // data属性キー
    DATA_ATTR : {
        ID   : "data-id",
        KEY  : "data-app-action-key",
        ROW  : "data-app-row",
        FORM : {
            KEY     : "data-app-form",
            NAME    : "data-app-name",
            DEFAULT : "data-app-form-default"
        }
    }
};

/** ---------------------------------------------------------------------------
 *  グローバルオブジェクト／変数
 *  -------------------------------------------------------------------------*/
/** 本アプリケーションのルートオブジェクト */
var WNote = {};
WNote.ajaxDefaultTimeout  = 20000; // デフォルトのAjax通信のタイムアウト(ミリ秒）
WNote.ajaxValidateTimeout = 2000;  // バリデーション時のAjax通信のタイムアウト(ミリ秒）

/** フォーム用のルートオブジェクト */
WNote.Form = {};
WNote.Form.formActionStatus = { // フォーム操作初期ステータス
    Before:  WNOTE.FORM_STATUS.INIT,
    Current: WNOTE.FORM_STATUS.INIT
}
WNote.Form.validator; // バリデーション用オブジェクト

/** ユーティリティ用のルートオブジェクト */
WNote.Util = {};
WNote.Util.Validate = {};

/** 各ページのルートオブジェクト */
var MyPage = {};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // START: Smart Admin - Style Template ------------------------------------
    // DO NOT REMOVE : GLOBAL FUNCTIONS!
    pageSetUp();
    // END: Smart Admin - Style Template --------------------------------------

    // イベント登録 -----------------------------------------------------------
    WNote.registerEvent('click', 'change-domain'  , WNote.changeDomainHandler);       // ドメイン変更イベント（ヘッダー部ドメイン選択イベント）
    WNote.registerEvent('click', 'side-list'      , WNote.sideListClickHandler);      // サイドリスト（Element/Parts/side-list）用クリックイベント
    WNote.registerEvent('click', 'side-datatable' , WNote.sideDatatableClickHandler); // サイドデータテーブル（Element/Parts/side-datatable）用クリックイベント
});

/** ---------------------------------------------------------------------------
 *  イベントハンドラ
 *  -------------------------------------------------------------------------*/
/** ドメイン変更イベントのハンドラ */
WNote.changeDomainHandler = function(event) {
    // フォームに変更後のIDを設定
    $('[' + WNOTE.DATA_ATTR.KEY + '="change-domain-id"]').val($(event.target).attr(WNOTE.DATA_ATTR.ID));

    // フォームの送信
    $('[' + WNOTE.DATA_ATTR.KEY + '="change-domain-form"]').submit();
}
/** サイドリスト（Element/Parts/side-list）用クリックイベントのハンドラ(使用箇所で実装) */
WNote.sideListClickHandler = function(event) {}
/** サイドリスト（Element/Parts/side-list）用クリックイベントのハンドラ(使用箇所で実装) */
WNote.sideDatatableClickHandler = function(event) {}

/** アラートメッセージ内のボタンクリック時のハンドラ(使用箇所で実装) */
WNote.showErrorYesHandler = function(event) {}
/** 警告メッセージ内のボタンクリック時のハンドラ(使用箇所で実装) */
WNote.showConfirmYesHandler = function(event) {}
WNote.showConfirmNoHandler  = function(event) {}

/** Ajax送信前処理のハンドラ（使用箇所で実装） - falseを返すと処理をキャンセルする */
WNote.ajaXSendBeforeSendHandler = function(XMLHttpRequest) {}

/** ---------------------------------------------------------------------------
 *  関数／メソッド（共通）
 *  -------------------------------------------------------------------------*/
/**
 * イベント登録
 *
 * @param {string} event   イベント名（例：click）
 * @param {string} key     データ属性キー名（data-app-action-key属性の値）
 * @param {object} handler イベントハンドラー
 */
WNote.registerEvent = function(event, key, handler) {
    $(document).on(event, '[' + WNOTE.DATA_ATTR.KEY + '="' + key + '"]' , handler);
}

/**
 * ログを表示する
 *
 * @param {object} msg メッセージ
 */
WNote.log = function(msg) {
    if (console) console.log(msg);
}

/**
 * アラートメッセージを表示する
 *
 * @param {string} msg メッセージ
 */
WNote.showErrorMessage = function(msg) {
    $.SmartMessageBox({
        title : "エラーが発生しました。",
        content : msg,
        buttons : '[Yes]'
    }, function(ButtonPressed) {
        if (ButtonPressed === "Yes") {
            WNote.showErrorYesHandler();
        }
    });
}

/**
 * 確認メッセージを表示する
 *
 * @param {string} msg メッセージ
 */
WNote.showConfirmMessage = function(msg) {
    $.SmartMessageBox({
        title : "確認メッセージ",
        content : msg,
        buttons : '[実行][キャンセル]'
    }, function(ButtonPressed) {
        if (ButtonPressed === "実行") {
            WNote.showConfirmYesHandler();
        }
        if (ButtonPressed === "キャンセル") {
            WNote.showConfirmNoHandler();
        }
    });
}

/**
 * 通知メッセージを表示する
 *
 * @param {string} msg メッセージ
 */
WNote.showNotificationMessage = function(msg) {
    $.smallBox({
        title : "通知",
        content : msg,
        color : "#5384AF",
        icon : "fa fa-bell",
        timeout: 5000
    });
}

/**
 * 通知（強調）メッセージを表示する
 *
 * @param {string} msg メッセージ
 */
WNote.showWarningMessage = function(msg) {
    $.smallBox({
        title : "注意",
        content : msg,
        color : "rgb(199, 145, 33)",
        icon : "fa fa-bell"
    });
}

/** ---------------------------------------------------------------------------
 *  関数／メソッド（API関連）
 *  -------------------------------------------------------------------------*/
/**
 * Ajax送信を行う(※汎用利用のケースでは、ajaxSendBasicの利用を検討してください)
 *
 * [useage]
 *   var prms = WNote.ajaxSend('/api/xxx', 'POST', {id: '1'});
 *   prms.done(function(data, dataType){
 *       // 成功時の処理
 *       if (dataType == "success") {
 *           console.log(data.data.result);
 *           console.log(data.data.message);
 *           console.log(data.data.param);
 *       }
 *   });
 *   prms.fail(function(XMLHttpRequest, textStatus, errorThrown){
 *       if (textStatus != "canceled") { // sendBeforeイベントのキャンセル以外のエラーを処理する場合は条件を追加する
 *           console.log(XMLHttpRequest.responseJSON.message); // responseJSONに返却されたオブジェクトが格納されている
 *           console.log(textStatus);  // "error"などといった文字列が設定されている
 *           console.log(errorThrown); // HTTPステータスコードに対応するエラーメッセージが入る（例："Unauthorized"など）
 *       }
 *   });
 *
 * @param {string} _url  送信先のURL
 * @param {string} _type GET or POST
 * @param {object} _data 送信するデータオブジェクト
 * @return {object} Promise
 */ 
WNote.ajaxSend = function(_url, _type, _data) {
    // Ajax送信
    return $.ajax({
        url        : _url,
        type       : _type,
        data       : _data,
        async      : true,
        dataType   : 'json',
        timeout    : WNote.ajaxDefaultTimeout,
        beforeSend : function(XMLHttpRequest) {
            // JWTをヘッダーに追加
            XMLHttpRequest.setRequestHeader ('Authorization', 'Bearer ' + $('#jwt').val());
            return WNote.ajaXSendBeforeSendHandler(XMLHttpRequest);
        }
    });
}

/**
 * 同期型のAjax送信を行い、結果を返す(※リモートValidation/Treeデータ取得用)
 *
 * @param {string} _url  送信先のURL
 * @param {string} _type GET or POST
 * @param {object} _data 送信するデータオブジェクト
 * 
 * @return {object} 成功時：結果データ/失敗時：false
 */ 
WNote.ajaxValidateSend = function(_url, _type, _data) {
    var response;

    try {
        // Ajax送信
        response = $.ajax({
            url        : _url,
            type       : _type,
            data       : _data,
            async      : false,
            dataType   : 'json',
            timeout    : WNote.ajaxValidateTimeout,
            beforeSend : function(XMLHttpRequest) {
                // JWTをヘッダーに追加
                XMLHttpRequest.setRequestHeader ('Authorization', 'Bearer ' + $('#jwt').val());
                return WNote.ajaXSendBeforeSendHandler(XMLHttpRequest);
            }
        }).responseText;
    } catch(e) {
        WNote.log(e);
        return false;
    }

    try {
        response = JSON.parse(response);
    } catch(e) {
        WNote.log(e);
        return false;
    }
    if(WNote.validateApiResponse(response)) {
        return response.data.param;
    }

    return false;
}

/**
 * 汎用的なAjax送信を行う
 *
 * @param {string}  _url  送信先のURL
 * @param {string}  _type GET or POST
 * @param {object}  _data 送信するデータオブジェクト
 * @param {boolean} _showLoading ローディング画面の表示有無（デフォルト：true）
 * @param {object}  _successHandler 成功時に呼び出すハンドラー
 * @param {object}  _failureHandler 失敗時に呼び出すハンドラー
 * @return {object} Promise
 */ 
WNote.ajaxSendBasic = function(_url, _type, _data, _showLoading, _successHandler, _failureHandler) {
    _showLoading    = (_showLoading)    ? _showLoading    : true;
    _successHandler = (_successHandler) ? _successHandler : WNote.ajaxSuccessHandler;
    _failureHandler = (_failureHandler) ? _failureHandler : WNote.ajaxFailureHandler;

    // ローディング画面表示
    if (_showLoading) {
        WNote.showLoading();
    }

    // Ajax送信
    var promise;
    try {
        promise = $.ajax({
            url        : _url,
            type       : _type,
            data       : _data,
            async      : true,
            dataType   : 'json',
            timeout    : WNote.ajaxDefaultTimeout,
            beforeSend : function(XMLHttpRequest) {
                // JWTをヘッダーに追加
                XMLHttpRequest.setRequestHeader ('Authorization', 'Bearer ' + $('#jwt').val());
                return WNote.ajaXSendBeforeSendHandler(XMLHttpRequest);
            }
        });
    } catch (e) {
        _failureHandler(e);
        WNote.log(e);
        return ;
    }

    // 送信成功時
    promise.done(function(data, dataType) {
        try {
            if (dataType="success") {
                if (!WNote.validateApiResponse(data) || !data.data.param) {
                    _failureHandler(data);
                    WNote.log(data);
                } else {
                    _successHandler(data);
                }
            }
        } catch(e) {
            _failureHandler(e);
            WNote.log(e);
        }
    });

    // 送信失敗時
    promise.fail(function(xhr, status, error) {
        try {
            if (status != "canceled") {
                var data = xhr.responseJSON;
                _failureHandler(data);
                if (data && data.message) WNote.log(error + ":" + data.message);
                WNote.log(data);
            }
        } catch(e) {
            _failureHandler(e);
            WNote.log(e);
        }
    });

    return promise;
}

/**
 * ajax送信時のbeforeSendオプションを追加する
 *
 * @param {object} _options ajax送信オプション
 * 
 * @return {object} ajax送信オプション
 */
WNote.ajaxAddSelect2Options = function(_options) {
    _options.beforeSend = function(XMLHttpRequest) {
            // JWTをヘッダーに追加
            XMLHttpRequest.setRequestHeader ('Authorization', 'Bearer ' + $('#jwt').val());
            return WNote.ajaXSendBeforeSendHandler(XMLHttpRequest);
    };
    _options.type = "POST";
    _options.delay = 300;
    return _options;
}

/**
 * Ajax送信成功時のデフォルトハンドラー
 *
 * @param {object} data 送信データ
 * @param {string} msg  成功時のメッセージ(未指定：デフォルトメッセージを利用)
 *
 */ 
WNote.ajaxSuccessHandler = function(data, msg) {
    WNote.hideLoading();
    msg = (msg) ? msg : WNote.ajaxSuccessMessage;
    WNote.showNotificationMessage(msg);
}
/** Ajax送信成功時のデフォルトメッセージ */
WNote.ajaxSuccessMessage = "正常に処理が完了しました。";

/**
 * Ajax送信失敗時のデフォルトハンドラー
 *
 * @param {object} data 送信データ
 * @param {string} msg  失敗時のメッセージ(未指定：デフォルトメッセージを利用)
 */ 
WNote.ajaxFailureHandler = function(data, msg) {
    WNote.hideLoading();
    msg = (msg) ? msg : WNote.ajaxFailureMessage;
    WNote.showErrorMessage(msg);
}
/** Ajax送信失敗時のデフォルトメッセージ */
WNote.ajaxFailureMessage = "エラーが発生した為に処理を継続することができません。管理者にお問い合わせください。";

/**
 * Ajax送信前検証時の警告表示メソッド
 * ※本ハンドラーはAjax送信イベントではなく、送信前の検証などで利用するメソッドです。
 *
 * @param {object} data 送信データ
 * @param {object} validate 検証結果で判断する場合に「WNote.Form.validateResultSet」の結果を設定
 * @param {string} msg  失敗時のメッセージ(未指定：デフォルトメッセージを利用)
 * @return {boolean} true:検証成功/false:検証失敗|未検証
 */ 
WNote.ajaxValidateWarning = function(data, validate, msg) {
    WNote.hideLoading();

    if (validate) {
        if (validate.result) {
            return true;
        } else {
            msg = (msg) ? msg : validate.msg;
        }
    }

    msg = (msg) ? msg : WNote.ajaxWarningMessage;
    WNote.showWarningMessage(msg);
    return false;
}
/** Ajax送信前警告時のデフォルトメッセージ */
WNote.ajaxWarningMessage = "入力内容に誤りがあります。入力内容をご確認ください。";

/**
 * APIのレスポンスの検証を行う
 *
 * @param {object} data APIのレスポンスデータ
 * @return {boolean} true:正常|false:異常（エラー）
 */ 
WNote.validateApiResponse = function(data) {
    if (!data
        || !data.data
        || !data.error
        || !data.error.code
        || data.error.code != "200")
    {
        return false;
    }

    if (data.error.code == "200"
        && !data.data.param)
    {
        return false;
    }

    return true;
}

/**
 * Ajax送信中のローディング画面を表示する
 *
 * @param {string} msg 表示メッセージ（デフォルト：読込中・・・）
 */ 
WNote.showLoading = function(_msg) {
    var msg = (_msg === undefined) ? '読込中・・・' : _msg;

    if ($('#api-loading').length === 0) {
        $('body').append(
            '<div id="api-loading">' +
                '<div class="api-loading-message">' + msg +
                '</div>' + 
            '</div>'
        );
    }
}

/**
 * Ajax送信中のローディング画面を非表示にする
 *
 */ 
WNote.hideLoading = function() {
    if ($('#api-loading').length > 0) {
        $('#api-loading').remove();
    }
}


/** ---------------------------------------------------------------------------
 *  関数／メソッド（Form関連）
 *  -------------------------------------------------------------------------*/
/**
 * 表示モードを変更する
 * 
 * @param {string} status WNOTE.FORM_STATUS定数の値（例：WNOTE.FORM_STATUS.ADD）
 */
WNote.Form.changeFormActionStatus = function(status) {
    WNote.Form.formActionStatus.Before  = WNote.Form.formActionStatus.Current;
    WNote.Form.formActionStatus.Current = status;
    WNote.Form.changeFormActionStatus.afterChange(status);
}
/**
 * 表示モード変更後のハンドラー（利用箇所で実装する）
 * 
 * @param {string} status WNOTE.FORM_STATUS定数の値（例：WNOTE.FORM_STATUS.ADD）
 */
WNote.Form.changeFormActionStatus.afterChange = function(status) {}


/**
 * 追加モードに変更する
 * 
 * @param {string} appFormKey "data-app-form"属性に指定したキー名（例：form-domain）
 */
WNote.Form.addMode = function(appFormKey) {
    if (WNote.Form.formActionStatus.Current === WNOTE.FORM_STATUS.ADD) {
        return ;
    }

    WNote.Util.removeAttrByFormAttr(appFormKey, 'disabled');

    WNote.Util.addClassByDataAttr('add-actions'    , 'hidden');
    WNote.Util.removeClassByDataAttr('edit-actions', 'hidden');
    WNote.Util.addClassByDataAttr('delete-actions' , 'hidden');
    WNote.Util.addClassByDataAttr('view-actions'   , 'hidden');

    WNote.Form.changeFormActionStatus(WNOTE.FORM_STATUS.ADD);
    WNote.Form.validateClear();
}
/**
 * 編集モードに変更する
 * 
 * @param {string} appFormKey "data-app-form"属性に指定したキー名（例：form-domain）
 */
WNote.Form.editMode = function(appFormKey) {
    if (WNote.Form.formActionStatus.Current === WNOTE.FORM_STATUS.EDIT) {
        return ;
    }

    WNote.Util.removeAttrByFormAttr(appFormKey, 'disabled');

    WNote.Util.addClassByDataAttr('add-actions'      , 'hidden');
    WNote.Util.removeClassByDataAttr('edit-actions'  , 'hidden');
    WNote.Util.removeClassByDataAttr('delete-actions', 'hidden');
    WNote.Util.addClassByDataAttr('view-actions'     , 'hidden');

    WNote.Form.changeFormActionStatus(WNOTE.FORM_STATUS.EDIT);
    WNote.Form.validateClear();
}

/**
 * 表示モードに変更する
 * 
 * @param {string} appFormKey "data-app-form"属性に指定したキー名（例：form-domain）
 */
WNote.Form.viewMode = function(appFormKey) {
    if (WNote.Form.formActionStatus.Current === WNOTE.FORM_STATUS.VIEW) {
        return ;
    }

    WNote.Util.addAttrByFormAttr(appFormKey, 'disabled', 'disabled');

    WNote.Util.removeClassByDataAttr('add-actions' , 'hidden');
    WNote.Util.addClassByDataAttr('edit-actions'   , 'hidden');
    WNote.Util.addClassByDataAttr('delete-actions' , 'hidden');
    WNote.Util.removeClassByDataAttr('view-actions', 'hidden');

    WNote.Form.changeFormActionStatus(WNOTE.FORM_STATUS.VIEW);
    WNote.Form.validateClear();
}

/**
 * 初期モードに変更する
 * 
 * @param {string} appFormKey "data-app-form"属性に指定したキー名（例：form-domain）
 */
WNote.Form.initMode = function(appFormKey) {
    if (WNote.Form.formActionStatus.Current === WNOTE.FORM_STATUS.INIT) {
        return ;
    }

    WNote.Util.addAttrByFormAttr(appFormKey, 'disabled', 'disabled');

    WNote.Util.removeClassByDataAttr('add-actions', 'hidden');
    WNote.Util.addClassByDataAttr('edit-actions'  , 'hidden');
    WNote.Util.addClassByDataAttr('delete-actions', 'hidden');
    WNote.Util.addClassByDataAttr('view-actions'  , 'hidden');

    WNote.Form.changeFormActionStatus(WNOTE.FORM_STATUS.INIT);
    WNote.Form.validateClear();
}

/**
 * 指定されたオブジェクトのキーに指定されたid属性を持つフォームにオブジェクトの値を設定する
 * 
 * @param {object} obj オブジェクト
 */
WNote.Form.setFormValues = function(obj) {
    $.each(obj, function(key, val) {
        $('#' + key).each(function(i, e) {
            if ($(e).attr('type') == 'checkbox') {
                if ($(e).attr(WNOTE.DATA_ATTR.ID) == val) {
                    $(e).prop('checked', true);
                }
            } else if ($(e).attr('type') == 'radio') {
                if ($(e).attr(WNOTE.DATA_ATTR.ID) == val) {
                    $(e).prop('checked', true);
                }
            } else if ($(e).hasClass('select2')) {
                var text_key = key.split('_');
                text_key = (text_key.length > 1) ? text_key[0] + '_text' : text_key + '_text';
                if (obj[text_key]) {
                    var option = new Option(obj[text_key], val, true, true);
                    $(e).append(option).trigger('change');
                }
            } else {
                $(e).val(val);
            }
        });
    });
}

/**
 * 指定された"data-app-form"属性を持つフォームの値をクリアする
 * 
 * @param {string} appFormKey "data-app-form"属性に指定したキー名（例：form-domain）
 */
WNote.Form.clearFormValues = function(appFormKey) {
    $('[' + WNOTE.DATA_ATTR.FORM.KEY + '="' + appFormKey + '"]').each(function(i, e) {
        var val = '';
        if ($(e).attr(WNOTE.DATA_ATTR.FORM.DEFAULT)
            && $(e).attr(WNOTE.DATA_ATTR.FORM.DEFAULT).length > 0) {
            val = $(e).attr(WNOTE.DATA_ATTR.FORM.DEFAULT);
        }
        if ($(e).attr('type') == 'checkbox' || $(e).attr('type') == 'radio') {
            if (val == "checked") {
                $(e).prop('checked', true);
            } else {
                $(e).prop('checked', false);
            }
        } else if ($(e).hasClass('select2')) {
            $(e).val(null).trigger('change');
        } else {
            $(e).val(val);
        }
    });
}

/**
 * 指定された"data-app-action-key"属性を持つデータテーブルなど一覧内のフォームの値を設定する
 * 
 * @param {string} appActionKey "data-app-action-key"属性に指定したキー名（例：form-checkbox）
 * @param {object} obj 設定値を持つオブジェクト
 * @param {string} key 値をオブジェクトから取得するキー名（カラム名）
 * @param {string} row 行を識別する値（"data-app-row"属性に指定した値）をオブジェクトから取得するキー名（カラム名） - WNOTE.DATA_ATTR.ROW
 */
WNote.Form.setTableFormValue = function(appActionKey, obj, key, row) {
    if (!obj || typeof obj !== 'object') {
        return ;
    }

    $('[' + WNOTE.DATA_ATTR.KEY + '="' + appActionKey + '"]').each(function(i, e) {
        // 行識別する場合、かつ、行が異なる場合はスキップする
        if (row && obj[row] && obj[row] != $(e).attr(WNOTE.DATA_ATTR.ROW)) {
            return true; // continue;
        }

        if ($(e).attr('type') == 'checkbox' || $(e).attr('type') == 'radio') {
            if (obj[key] && obj[key] == $(e).attr(WNOTE.DATA_ATTR.ID)) {
                $(e).prop('checked', true);
            }
        } else {
            if (obj[key]) {
                $(e).val(obj[key]);
            }
        }
    });
}

/**
 * 指定された"data-app-action-key"属性を持つデータテーブルなど一覧内のフォームの値を設定する
 * (オブジェクトが複数レコードの場合はこちらを利用）
 * 
 * @param {string} appActionKey "data-app-action-key"属性に指定したキー名（例：form-checkbox）
 * @param {object} obj 設定値を持つオブジェクト
 * @param {string} key 値をオブジェクトから取得するキー名（カラム名）
 * @param {string} row 行を識別する値（"data-app-row"属性に指定した値）をオブジェクトから取得するキー名（カラム名）
 */
WNote.Form.setTableFormValues = function(appActionKey, obj, key, row) {
    if (!obj || typeof obj !== 'object') {
        return ;
    }

    $(obj).each(function(i, e) {
        WNote.Form.setTableFormValue(appActionKey, e, key, row);
    });
}

/**
 * 指定された"data-app-form"属性を持つフォームの値よりAjax送信用のデータを作成する
 * 
 * Formのname属性に指定された値を元にキーと値の組み合わせデータを作成する
 * name属性の名前が「.」（ドット）で区切られている場合、キーを分割する
 * name属性が同一のフォームが複数ある場合はレコード形式でデータを作成する
 * 
 * @param {string} appFormKey "data-app-form"属性に指定したキー名（例：form-domain）
 * @return {object} Ajax送信用のデータオブジェクト
 */
WNote.Form.createAjaxData = function(appFormKey) {
    var data = {};

    $('[' + WNOTE.DATA_ATTR.FORM.KEY + '="' + appFormKey + '"]').each(function(i, e) {
        // 通常は「name」属性の値より送信フィールドを作成するが、radioボタンなどをテーブルで利用する場合、
        // 「name」を同じにしなければならない為、このようなケースでは、「data-app-name」属性に指定された値を利用する
        var attr_name = ($(e).attr(WNOTE.DATA_ATTR.FORM.NAME)) ? $(e).attr(WNOTE.DATA_ATTR.FORM.NAME) : $(e).attr('name');

        var keys = attr_name.split('.');
        var val  = $(e).val();

        if ($(e).attr('type') == 'checkbox' && $(e).prop('checked') == false) {
            return true; // チェックボックスでチェックのついていないデータは送信しない(return trueはeach利用時のcontinueの代用)
        }
        if ($(e).attr('type') == 'radio' && $(e).prop('checked') == false) {
            return true; // ラジオボタンでチェックのついていないデータは送信しない
        }

        // データを作成する
        data = WNote.Util.dotS2AWithValue(data, keys.slice(), val);
    });

    return data;
}

/**
 * 検証結果を作成する
 * 
 * Formのバリデートの共通結果セットを作成する
 * バリデート結果としてエラーメッセージ（正常の場合はnull or ''）を受け取り、共通の結果セットを作成する
 * 
 * @param {object} message エラーメッセージ（複数ある場合は配列で受け取る）
 * @return {object} 結果セットオブジェクト（result: true/false, msg: エラーメッセージ）
 */
WNote.Form.validateResultSet = function(message) {
    var validate = {result: false, msg: ''};

    validate.msg = message;
    if (!message || message.length == 0) {
        validate.result = true;
    }

    return validate;
}

/**
 * 検証結果の表示をクリアする
 * 
 */
WNote.Form.validateClear = function() {
    if (WNote.Form.validator) {
        WNote.Form.validator.resetForm();
    }
}

/** ---------------------------------------------------------------------------
 *  ユーティリティ（DOM関連）
 *  -------------------------------------------------------------------------*/
/**
 * 指定されたセレクタを持つタグに指定したクラスを追加する
 * 
 * @param {string} selector セレクタ
 * @param {string} className 追加するクラス名
 */
WNote.Util.addClass = function(selector, className) {
    $(selector).each(function(i, e) {
        $(e).addClass(className);
    });
}

/**
 * 指定されたセレクタを持つタグより指定したクラスを削除する
 * 
 * @param {string} selector セレクタ
 * @param {string} className 削除するクラス名
 */
WNote.Util.removeClass = function(selector, className) {
    $(selector).each(function(i, e) {
        $(e).removeClass(className);
    });
}

/**
 * 指定された属性とキーを持つタグに指定したクラスを追加する
 * 
 * @param {string} attr 属性
 * @param {string} key  属性の値
 * @param {string} className 追加するクラス名
 */
WNote.Util.addClassByAttr = function(attr, key, className) {
    WNote.Util.addClass('[' + attr + '="' + key + '"]', className);
}

/**
 * 指定された属性とキーを持つタグより指定したクラスを削除する
 * 
 * @param {string} attr 属性
 * @param {string} key  属性の値
 * @param {string} className 削除するクラス名
 */
WNote.Util.removeClassByAttr = function(attr, key, className) {
    WNote.Util.removeClass('[' + attr + '="' + key + '"]', className);
}

/**
 * 指定された"data-app-action-key"属性のキーを持つタグに指定したクラスを追加する
 * 
 * @param {string} key "data-app-action-key"属性の値（例：edit-actions）
 * @param {string} className 追加するクラス名
 */
WNote.Util.addClassByDataAttr = function(key, className) {
    WNote.Util.addClassByAttr(WNOTE.DATA_ATTR.KEY, key, className);
}

/**
 * 指定された"data-app-action-key"属性のキーを持つタグより指定したクラスを削除する
 * 
 * @param {string} key "data-app-action-key"属性の値（例：edit-actions）
 * @param {string} className 削除するクラス名
 */
WNote.Util.removeClassByDataAttr = function(key, className) {
    WNote.Util.removeClassByAttr(WNOTE.DATA_ATTR.KEY, key, className);
}

/**
 * 指定された"data-app-form"属性のキーを持つタグに指定したクラスを追加する
 * 
 * @param {string} key "data-app-form"属性の値（例：domain-form）
 * @param {string} className 追加するクラス名
 */
WNote.Util.addClassByFormAttr = function(key, className) {
    WNote.Util.addClassByAttr(WNOTE.DATA_ATTR.FORM.KEY, key, className);
}

/**
 * 指定された"data-app-form"属性のキーを持つタグの指定したクラスを削除する
 * 
 * @param {string} key "data-app-form"属性の値（例：domain-form）
 * @param {string} className 削除するクラス名
 */
WNote.Util.removeClassByFormAttr = function(key, className) {
    WNote.Util.removeClassByAttr(WNOTE.DATA_ATTR.FORM.KEY, key, className);
}

/**
 * 指定されたセレクタを持つタグに指定した属性と値を追加する
 * 
 * @param {string} selector セレクタ
 * @param {string} attr 追加する属性
 * @param {string} key 追加する属性の値
 */
WNote.Util.addAttr = function(selector, attr, key) {
    $(selector).each(function(i, e) {
        $(e).attr(attr, key);
    });
}

/**
 * 指定されたセレクタを持つタグより指定した属性を削除する
 * 
 * @param {string} selector セレクタ
 * @param {string} attr 削除する属性
 */
WNote.Util.removeAttr = function(selector, attr) {
    $(selector).each(function(i, e) {
        $(e).removeAttr(attr);
    });
}

/**
 * 指定された属性とキーを持つタグに指定した属性と値を追加する
 * 
 * @param {string} attr 属性
 * @param {string} key  属性の値
 * @param {string} addAttr 追加する属性
 * @param {string} addKey  追加する属性の値
 */
WNote.Util.addAttrByAttr = function(attr, key, addAttr, addKey) {
    WNote.Util.addAttr('[' + attr + '="' + key + '"]', addAttr, addKey);
}

/**
 * 指定された属性とキーを持つタグより指定した属性を削除する
 * 
 * @param {string} attr 属性
 * @param {string} key  属性に指定した値
 * @param {string} removeAttr 削除する属性
 */
WNote.Util.removeAttrByAttr = function(attr, key, removeAttr) {
    WNote.Util.removeAttr('[' + attr + '="' + key + '"]', removeAttr);
}

/**
 * 指定された"data-app-action-key"属性のキーを持つタグに指定した属性と値を追加する
 * 
 * @param {string} key "data-app-action-key"属性に指定した値（例：edit-actions）
 * @param {string} addAttr 追加する属性
 */
WNote.Util.addAttrByDataAttr = function(key, addAttr, addKey) {
    WNote.Util.addAttrByAttr(WNOTE.DATA_ATTR.KEY, key, addAttr, addKey);
}

/**
 * 指定された"data-app-action-key"属性のキーを持つタグより指定した属性を削除する
 * 
 * @param {string} key "data-app-action-key"属性に指定した値（例：edit-actions）
 * @param {string} removeAttr 削除する属性
 */
WNote.Util.removeAttrByDataAttr = function(key, removeAttr) {
    WNote.Util.removeAttrByAttr(WNOTE.DATA_ATTR.KEY, key, removeAttr);
}

/**
 * 指定された"data-app-form"属性のキーを持つタグに指定した属性と値を追加する
 * 
 * @param {string} key "data-app-form"属性に指定した値（例：form-domain）
 * @param {string} addAttr 追加する属性
 */
WNote.Util.addAttrByFormAttr = function(key, addAttr, addKey) {
    WNote.Util.addAttrByAttr(WNOTE.DATA_ATTR.FORM.KEY, key, addAttr, addKey);
}

/**
 * 指定された"data-app-form"属性のキーを持つタグより指定した属性を削除する
 * 
 * @param {string} key "data-app-form"属性に指定した値（例：form-domain）
 * @param {string} removeAttr 削除する属性
 */
WNote.Util.removeAttrByFormAttr = function(key, removeAttr) {
    WNote.Util.removeAttrByAttr(WNOTE.DATA_ATTR.FORM.KEY, key, removeAttr);
}

/** ---------------------------------------------------------------------------
 *  ユーティリティ（固有）
 *  -------------------------------------------------------------------------*/
/**
 * "."（ドット）で区切られた文字列より連想配列を作成する
 * 
 * 使用例)
 *     var obj = WNote.Util.dotS2A({}, "datas.domain.apps.id");
 * 出力例）
 *     {
 *         datas: {
 *             domain: {
 *                 apps: {
 *                     id: {}
 *                 }
 *             }
 *         }
 *     }
 * 
 * @param {string} obj  作成する連想配列（元配列が不要な場合は、「{}」を指定）
 * @param {string} keys ドットで区切られた文字列
 * @return {object} ドットで区切られた文字列より作成した連想配列
 */
WNote.Util.dotS2A = function(obj, keys) {
    if (keys.length == 0) {
        return ;
    }

    // 最初のキーを取得する
    var key = keys.shift();

    // キーを追加する
    if (!obj[key]) obj[key] = {};

    // 再帰的に処理を行う
    WNote.Util.dotS2A(obj[key], keys);

    return obj;
}
/**
 * "."（ドット）で区切られた文字列より連想配列を作成し、値を設定する
 * 
 * @param {string} obj  作成する連想配列（元配列が不要な場合は、「{}」を指定）
 * @param {string} keys ドットで区切られた文字列
 * @param {string} val  保存する値
 * @return {object} ドットで区切られた文字列より作成した連想配列
 */
WNote.Util.dotS2AWithValue = function(obj, keys, val) {
    // 最初のキーを取得する
    var key = keys.shift();

    // キーの追加とデータ設定
    if (keys.length == 0) {
        if (obj[key]) {
            var tmp = obj[key];
            if (Array.isArray(tmp)) {
                tmp.push(val);
            } else {
                tmp = new Array(tmp, val);
            }
            obj[key] = tmp;
        } else {
            obj[key] = val;
        }
        return obj;
    } else if (!obj[key]) {
        obj[key] = {};
    }

    // 再帰的に処理を行う
    WNote.Util.dotS2AWithValue(obj[key], keys, val);

    return obj;
}

/** ---------------------------------------------------------------------------
 *  ユーティリティ（検証）
 *  -------------------------------------------------------------------------*/
/**
 * jquery validatorの基本オプションを設定したオブジェクト
 *
 * @return {object} jquery validatorの基本オプション
 */
WNote.Util.Validate.ValidatorOptions = function() {
    return {
        errorClass    : 'invalid',
        errorElement  : 'em',
        highlight     : WNote.Util.Validate.HighlightHandler,
        unhighlight   : WNote.Util.Validate.UnhighlightHandler,
        rules         : {},
        messages      : {},
        errorPlacement: WNote.Util.Validate.ErrorPlacementHandler,
        submitHandler : function(form) {},
        onsubmit      : false,
        onkeyup       : false,
        onfocusout    : false,
        onclick       : false
    };
}

/**
 * jquery validatorオプションの「highlight」オプションのハンドラー
 * 
 * @param {object} element 要素オブジェクト
 */
WNote.Util.Validate.HighlightHandler = function(element) {
    // $(element).parent().removeClass('state-success').addClass("state-error");
    $(element).parent().addClass("state-error");
    $(element).removeClass('valid');
}

/**
 * jquery validatorオプションの「unhighlight」オプションのハンドラー
 * 
 * @param {object} element 要素オブジェクト
 */
WNote.Util.Validate.UnhighlightHandler = function(element) {
    // $(element).parent().removeClass("state-error").addClass('state-success');
    $(element).parent().removeClass("state-error");
    $(element).addClass('valid');
}
/**
 * jquery validatorオプションの「errorPlacement」オプションのハンドラー
 * 
 * @param {object} error エラーオブジェクト
 * @param {object} element 要素オブジェクト
 */
WNote.Util.Validate.ErrorPlacementHandler = function(error, element) {
    error.insertAfter(element.parent());
}

