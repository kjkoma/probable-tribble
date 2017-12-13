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
WNOTE.TREE.ORGANIZATION = {
    SELECT_ID: 'select-customer'
};

/** ---------------------------------------------------------------------------
 *  グローバルオブジェクト／変数
 *  -------------------------------------------------------------------------*/
/** 本アプリケーションのルートオブジェクト */
WNote.Tree.Organization = {};
WNote.Tree.Organization.Customer = {
    value: -1,
    text : ""
};
WNote.Tree.Organization.Root = "";

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // ルートID設定
    WNote.Tree.Organization.Root
        = WNOTE.TREE.TYPES.ROOT + "_" + WNote.Tree.Organization.Customer.value;

    // ルートノードに資産管理会社を設定
    WNote.Tree.Organization.changeCustomer(null);

    /** イベント登録 */
    $('#' + WNOTE.TREE.ORGANIZATION.SELECT_ID).on('change', WNote.Tree.Organization.changeCustomerHandler);
});

/** ---------------------------------------------------------------------------
 *  イベントハンドラ
 *  -------------------------------------------------------------------------*/
/** ルート選択時のイベント処理（実装） */
WNote.Tree.nodeActivateRoot = function(node) {
    // サーバーよりルート配下の組織を取得
    var result = WNote.Tree.Organization.getChildren(
        '/api/master/admin/api-organizations/root',
        {
            'customer_id': WNote.Tree.Organization.Customer.value
        }
    );

    // 結果データよりノードを展開する
    if (result) {
        WNote.Tree.Organization.expand(WNote.Tree.node(node.key), result);
    }

    // ハンドラーを呼び出す
    WNote.Tree.Organization.nodeActivateRootHandler(node);
}
/** ルート選択時のイベントハンドラー（利用側で実装） */
WNote.Tree.Organization.nodeActivateRootHandler = function(node) {}

/** アイテム選択時のイベント処理（実装） */
WNote.Tree.nodeActivateItem = function(node) {
    // サーバーより配下の組織を取得
    var result = WNote.Tree.Organization.getChildren(
        '/api/master/admin/api-organizations/children',
        {
            'organization_id': node.data.organization_id
        }
    );

    // 結果データよりノードを展開する
    if (result) {
        WNote.Tree.Organization.expand(WNote.Tree.node(node.key), result);
    }

    // ハンドラーを呼び出す
    WNote.Tree.Organization.nodeActivateItemHandler(node);
}
/** アイテム選択時のイベントハンドラー（利用側で実装） */
WNote.Tree.Organization.nodeActivateItemHandler = function(node) {}

/** ---------------------------------------------------------------------------
 *  イベント処理（会社選択）
 *  -------------------------------------------------------------------------*/
/** 資産管理会社変更時のイベント処理 */
WNote.Tree.Organization.changeCustomerHandler = function(event) {
    // ノードクリア
    WNote.Tree.root().removeChildren();

    // 資産管理会社変更
    WNote.Tree.Organization.changeCustomer(event);
}

/** 資産管理会社変更処理 */
WNote.Tree.Organization.changeCustomer = function(event) {
    // 選択会社設定
    WNote.Tree.Organization.Customer.text
        = $('#' + WNOTE.TREE.ORGANIZATION.SELECT_ID).children(':selected').text();
    WNote.Tree.Organization.Customer.value
        = $('#' + WNOTE.TREE.ORGANIZATION.SELECT_ID).children(':selected').val();

    // ルートID設定
    WNote.Tree.Organization.Root
        = WNOTE.TREE.TYPES.ROOT + "_" + WNote.Tree.Organization.Customer.value;

    // ルートノードを追加
    WNote.Tree.root().addNode(WNote.Tree.createNode(
        WNote.Tree.Organization.Customer.text,
        WNote.Tree.Organization.Root,
        WNOTE.TREE.TYPES.ROOT,
        {},
        true, // folder
        null  // childNodes
    ));

    // ツリービューのルート選択
    WNote.Tree.activate(WNote.Tree.Organization.Root);
}

/** ---------------------------------------------------------------------------
 *  関数／メソッド（共通）
 *  -------------------------------------------------------------------------*/
/**
 * サーバーより組織を取得する
 *
 * @param {string} url   呼び出し先のURL
 * @param {object} data  送信データのオブジェクト
 * @return {object} サーバーのレスポンスデータ
 */
WNote.Tree.Organization.getChildren = function(url, data) {
    // サーバーよりルート配下の組織を取得
    var result = WNote.ajaxValidateSend(
        url, 'POST', data
    );

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、選択された組織配下の情報が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * サーバーレスポンスデータよりノードを展開する
 *
 * @param {object} node FancytreeNodeオブジェクト（展開するノード）
 * @param {object} result サーバーレスポンスデータ
 */
WNote.Tree.Organization.expand = function(node, result) {
    // 子孫を削除
    node.removeChildren();

    // 結果データよりノードを追加
    $(result.organizations).each(function(index, item) {
        node.addNode(WNote.Tree.createNode(
            item['ancestor_name'],
            item['id'],
            WNOTE.TREE.TYPES.ITEM,
            {
                'organization_id': item['descendant']
            },
            true, // folder
            null   // childNodes
        ));
    });
}


