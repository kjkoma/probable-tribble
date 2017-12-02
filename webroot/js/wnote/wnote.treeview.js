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
WNOTE.TREEVIEW = {
    ID: 'treeview',
    SELECT_ID: 'select-customer'
};

/** ---------------------------------------------------------------------------
 *  グローバルオブジェクト／変数
 *  -------------------------------------------------------------------------*/
/** 本アプリケーションのルートオブジェクト */
WNote.Treeview = {};
WNote.Treeview.Tree = null;
WNote.Treeview.Options = {
    data: [],
    onhoverColor     : 'rgba(192,192,192,0.3)',
    selectedBackColor: 'rgba(66,139,202,0.6)'
    onNodeSelected   : function(event, data) {
        WNote.Treeview.OnNodeSelected(event, data);
    }
};
WNote.Treeview.SelectedCustomer = {
    value: -1,
    text : ""
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {
    // 初期選択会社
    WNote.Treeview.SelectedCustomer.text  = $('#' + WNOTE.TREEVIEW.SELECT_ID).children(':selected').text();
    WNote.Treeview.SelectedCustomer.value = $('#' + WNOTE.TREEVIEW.SELECT_ID).children(':selected').val();

    // ツリービューオブジェクトの生成
    WNote.Treeview.Tree = $('#' + WNOTE.TREEVIEW.ID);
    WNote.Treeview.Options.data[0] = WNote.Treeview.createNote(
        WNote.Treeview.SelectedCustomer.text,
        WNote.Treeview.SelectedCustomer.value,
        null
    );
    WNote.Treeview.Tree.treeview(WNote.Treeview.Options);

    /** 各種操作イベント登録 */
    WNote.registerEvent('change', 'select-customer', WNote.Treeview.changeCustomer);
});

/** ---------------------------------------------------------------------------
 *  イベントハンドラ
 *  -------------------------------------------------------------------------*/
/** 資産管理会社変更時のイベント処理 */
WNote.Treeview.changeCustomer = function(event) {
    console.log($(event.target).val());
}

/** ツリービュー選択時のイベント処理 */
WNote.Treeview.OnNodeSelected = function(event, data) {
    WNote.Treeview.OnNodeSelectedHandler(event, data);
}
/** ツリービュー選択時のイベントハンドラー（利用側で実装） */
WNote.Treeview.OnNodeSelectedHandler = function(event, data) {
}

/** ---------------------------------------------------------------------------
 *  関数／メソッド（共通）
 *  -------------------------------------------------------------------------*/
/**
 * ツリービューのノードを作成する
 *
 * @param {string} text    表示名称
 * @param {string} value   表示名称の値
 * @param {object} childs  子ノード（存在しない場合はnull）
 */
WNote.Treeview.createNote = function(text, value, childs)
{
    var node = {
        text: text,
        value: value,
        nodes: childs
    };

    return node;
}
