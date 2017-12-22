/**
 * フォームを扱うクラス
 *
 * [依存ライブラリ]
 *   - jquery.js
 *   - wnote.js
 *
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
 *  ルートオブジェクトの作成
 *  -------------------------------------------------------------------------*/
WNote.Lib = WNote.Lib || [];

/** ---------------------------------------------------------------------------
 *  コンストラクタ
 *  -------------------------------------------------------------------------*/
/**
 * コンストラクタ
 *
 * @param {array} actionKeys 操作アクションのdata-app-action-keyの値オブジェクト
 *     actionKeys = {
 *         'add'    : 'add-action',
 *         'edit'   : 'edit-action',
 *         'view'   : 'view-action',
 *         'delete' : 'delete-action'
 *     }
 */
WNote.Lib.Form = function(actionKeys) {
    this.actionKeys = actionKeys
}

/** ---------------------------------------------------------------------------
 *  プロパティ
 *  -------------------------------------------------------------------------*/
/**
 * アクションキープロパティ
 */
WNote.Lib.Form.prototype.actionKeys = {
    'add'    : '',
    'edit'   : '',
    'view'   : '',
    'delete' : '',
};

/**
 * フォームキープロパティ
 */
WNote.Lib.Form.prototype.formKey = '';

/**
 * 現在のステータスプロパティ
 */
WNote.Lib.Form.prototype.current = WNOTE.FORM_STATUS.INIT;

/**
 * 直前のステータスプロパティ
 */
WNote.Lib.Form.prototype.before = WNOTE.FORM_STATUS.INIT;

/**
 * バリデータープロパティ
 * 
 * jquery validatorのインスタンスを設定する
 */
WNote.Lib.Form.prototype.validator = null;

/** ---------------------------------------------------------------------------
 *  関数／メソッド（モード設定）
 *  -------------------------------------------------------------------------*/
/**
 * 表示モードを変更する
 * 
 * @param {string} status WNOTE.FORM_STATUS定数の値（例：WNOTE.FORM_STATUS.ADD）
 */
WNote.Lib.Form.prototype.changeFormActionStatus = function(status) {
    this.before  = this.current;
    this.current = status;
    this.afterChangeHandler(status);
}
/**
 * 表示モード変更後のハンドラー（利用箇所で実装する）
 * 
 * @param {string} status WNOTE.FORM_STATUS定数の値（例：WNOTE.FORM_STATUS.ADD）
 */
WNote.Lib.Form.prototype.afterChangeHandler = function(status) {}

/**
 * 追加モードに変更する
 * 
 * @param {string} appFormKey "data-app-form"属性に指定したキー名（例：form-domain）
 */
WNote.Lib.Form.prototype.addMode = function(appFormKey) {
    if (this.current === WNOTE.FORM_STATUS.ADD) {
        this.addModeExtend(); // 拡張用
        return ;
    }

    WNote.Util.removeAttrByFormAttr(appFormKey, 'disabled');

    WNote.Util.addClassByDataAttr(this.actionKeys.add    , 'hidden');
    WNote.Util.removeClassByDataAttr(this.actionKeys.edit, 'hidden');
    WNote.Util.addClassByDataAttr(this.actionKeys.delete , 'hidden');
    WNote.Util.addClassByDataAttr(this.actionKeys.view   , 'hidden');

    this.changeFormActionStatus(WNOTE.FORM_STATUS.ADD);
    this.validateClear();

    this.addModeExtend(); // 拡張用
}
/** 追加モード処理拡張用（利用側にて実装） */
WNote.Lib.Form.prototype.addModeExtend = function() {}

/**
 * 編集モードに変更する
 * 
 * @param {string} appFormKey "data-app-form"属性に指定したキー名（例：form-domain）
 */
WNote.Lib.Form.prototype.editMode = function(appFormKey) {
    if (this.current === WNOTE.FORM_STATUS.EDIT) {
        this.editModeExtend(); // 拡張用
        return ;
    }

    WNote.Util.removeAttrByFormAttr(appFormKey, 'disabled');

    WNote.Util.addClassByDataAttr(this.actionKeys.add      , 'hidden');
    WNote.Util.removeClassByDataAttr(this.actionKeys.edit  , 'hidden');
    WNote.Util.removeClassByDataAttr(this.actionKeys.delete, 'hidden');
    WNote.Util.addClassByDataAttr(this.actionKeys.view     , 'hidden');

    this.changeFormActionStatus(WNOTE.FORM_STATUS.EDIT);
    this.validateClear();

    this.editModeExtend(); // 拡張用
}
/** 編集モード処理拡張用（利用側にて実装） */
WNote.Lib.Form.prototype.editModeExtend = function() {}

/**
 * 表示モードに変更する
 * 
 * @param {string} appFormKey "data-app-form"属性に指定したキー名（例：form-domain）
 */
WNote.Lib.Form.prototype.viewMode = function(appFormKey) {
    if (this.current === WNOTE.FORM_STATUS.VIEW) {
        this.viewModeExtend(); // 拡張用
        return ;
    }

    WNote.Util.addAttrByFormAttr(appFormKey, 'disabled', 'disabled');

    WNote.Util.removeClassByDataAttr(this.actionKeys.add , 'hidden');
    WNote.Util.addClassByDataAttr(this.actionKeys.edit   , 'hidden');
    WNote.Util.addClassByDataAttr(this.actionKeys.delete , 'hidden');
    WNote.Util.removeClassByDataAttr(this.actionKeys.view, 'hidden');

    this.changeFormActionStatus(WNOTE.FORM_STATUS.VIEW);
    this.validateClear();

    this.viewModeExtend(); // 拡張用
}
/** 表示モード処理拡張用（利用側にて実装） */
WNote.Lib.Form.prototype.viewModeExtend = function() {}

/**
 * 初期モードに変更する
 * 
 * @param {string} appFormKey "data-app-form"属性に指定したキー名（例：form-domain）
 */
WNote.Lib.Form.prototype.initMode = function(appFormKey) {
    if (this.current === WNOTE.FORM_STATUS.INIT) {
        this.initModeExtend(); // 拡張用
        return ;
    }

    WNote.Util.addAttrByFormAttr(appFormKey, 'disabled', 'disabled');

    WNote.Util.removeClassByDataAttr(this.actionKeys.add, 'hidden');
    WNote.Util.addClassByDataAttr(this.actionKeys.edit  , 'hidden');
    WNote.Util.addClassByDataAttr(this.actionKeys.delete, 'hidden');
    WNote.Util.addClassByDataAttr(this.actionKeys.view  , 'hidden');

    this.changeFormActionStatus(WNOTE.FORM_STATUS.INIT);
    this.validateClear();

    this.initModeExtend(); // 拡張用
}
/** 初期モード処理拡張用（利用側にて実装） */
WNote.Lib.Form.prototype.initModeExtend = function() {}

/**
 * 検証結果の表示をクリアする
 * 
 */
WNote.Lib.Form.prototype.validateClear = function() {
    if (this.validator) {
        this.validator.resetForm();
    }
}

