/**
 * ツリーを扱う為の基底となるライブラリ
 * 
 * [依存ライブラリ]
 *   - jquery.js
 *   - jquery.ui.js
 *   - fancytree-all.min.js
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
 *  定数
 *  -------------------------------------------------------------------------*/
WNOTE.TREE = {
    ID: 'tree',
    TYPES: {
        ROOT   : 'root',   // ルート階層を表すノード
        BRANCH : 'branch', // 階層を表すノード
        ITEM   : 'item',   // 階層の一部、かつ、値であるノード
        VALUE  : 'value'   // 階層ではなく値であるノード
    }
};

/** ---------------------------------------------------------------------------
 *  グローバルオブジェクト／変数
 *  -------------------------------------------------------------------------*/
/** 本アプリケーションのルートオブジェクト */
WNote.Tree = {};
WNote.Tree.Selector = null;
WNote.Tree.Instance = null;
WNote.Tree.Options = {
    extensions: ["filter"],
    selectMode: 1, // single (2:multi,3:multi-hier)
    source    : [],
    activate  : function(event, data) {
        WNote.Tree.nodeActivate(event, data);
    },
    beforeExpand : function(event, data) {
        if (!data.node.isExpanded()) {
            WNote.Tree.activate(data.node.key);
        }
    }
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // ツリーオブジェクトの生成
    WNote.Tree.Selector = $('#' + WNOTE.TREE.ID);
    WNote.Tree.Selector.fancytree(WNote.Tree.Options);
    WNote.Tree.Instance = WNote.Tree.Selector.fancytree('getTree');
    $(".fancytree-container").addClass("fancytree-connectors"); // コネクタの表示
});

/** ---------------------------------------------------------------------------
 *  イベントハンドラ
 *  -------------------------------------------------------------------------*/
/** ノード選択時のイベントハンドラー（利用側で実装） */
WNote.Tree.nodeActivateHandler = function(event, data) {
}
/** ノード選択時のイベント処理 */
WNote.Tree.nodeActivate = function(event, data) {
    WNote.Tree.nodeActivateHandler(event, data);

    switch (data.node.data.type) {
        case undefined:
            WNote.Tree.nodeActivateRoot(data.node);
            break;
        case WNOTE.TREE.TYPES.ROOT:
            WNote.Tree.nodeActivateRoot(data.node);
            break;
        case WNOTE.TREE.TYPES.BRANCH:
            WNote.Tree.nodeActivateBranch(data.node);
            break;
        case WNOTE.TREE.TYPES.ITEM:
            WNote.Tree.nodeActivateItem(data.node);
            break;
        case WNOTE.TREE.TYPES.VALUE:
            WNote.Tree.nodeActivateValue(data.node);
            break;
    }
}
/** (ルート)ノード選択時のイベント処理（利用側で実装） */
WNote.Tree.nodeActivateRoot = function(node) {}
/** (ブランチ)ノード選択時のイベント処理（利用側で実装） */
WNote.Tree.nodeActivateBranch = function(node) {}
/** (アイテム)ノード選択時のイベント処理（利用側で実装） */
WNote.Tree.nodeActivateItem = function(node) {}
/** (値)ノード選択時のイベント処理（利用側で実装） */
WNote.Tree.nodeActivateValue = function(node) {}


/** ---------------------------------------------------------------------------
 *  関数／メソッド（共通）
 *  -------------------------------------------------------------------------*/
/**
 * ルートノードを取得する
 *
 * @return {object} FancytreeNodeオブジェクト（ルート）
 */
WNote.Tree.root = function()
{
    return WNote.Tree.Instance.getRootNode();
}
/**
 * ノードを取得する
 *
 * @return {string|number} nodeId ノードID
 */
WNote.Tree.node = function(nodeId)
{
    return WNote.Tree.Instance.getNodeByKey(nodeId);
}

/**
 * アクティブノードを取得する
 *
 * @return {object} FancytreeNodeオブジェクト
 */
WNote.Tree.active = function()
{
    return WNote.Tree.Instance.getActiveNode();
}

/**
 * 現在のアクティブノードの親ノードを取得する
 *
 * @return {object} FancytreeNodeオブジェクト
 */
WNote.Tree.parent = function()
{
    var active = WNote.Tree.active();
    return active.getParent();
}

/**
 * ツリーノードをアクティブ化する
 *
 * @param {string|number} nodeId ノードID
 */
WNote.Tree.activate = function(nodeId)
{
    WNote.Tree.Instance.activateKey(nodeId);
}

/**
 * ツリーノードをアクティブ化後に展開する
 *
 * @param {string|number} nodeId ノードID
 */
WNote.Tree.expand = function(nodeId)
{
    WNote.Tree.activate(nodeId);
    var node = WNote.Tree.node(nodeId);
    node.navigate($.ui.keyCode.RIGHT);
}

/**
 * 現在のアクティブノードの親ノードをアクティブ化して展開する
 *
 */
WNote.Tree.activateParent = function()
{
    var parent = WNote.Tree.parent();
    WNote.Tree.expand(parent.key);
}

/**
 * ノードを作成する
 *
 * @param {string}  text     表示名称
 * @param {string}  key      表示名称のキー
 * @param {string}  type     表示タイプ（root or item or value）
 * @param {object}  attr     属性データ
 * @param {boolean} folder   true:フォルダアイコン/false:ファイルアイコン
 * @param {object}  children 子ノード（存在しない場合はnull）
 */
WNote.Tree.createNode = function(text, key, type, attr, folder, children)
{
    return {
        title: text,
        key: key,
        type: type,
        data: attr,
        folder: folder,
        children: children
    };
}

/**
 * サーバーより指定ノード配下の情報を取得する
 *
 * @param {string} url   呼び出し先のURL
 * @param {object} data  送信データのオブジェクト
 * @return {object} サーバーのレスポンスデータ
 */
WNote.Tree.getChildren = function(url, data) {
    // サーバーよりノード配下の情報を取得
    var result = WNote.ajaxValidateSend(
        url, 'POST', data
    );

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、選択されたノード配下の情報が取得できませんでした。');
        return null;
    }

    return result;
}
