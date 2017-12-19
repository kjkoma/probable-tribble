/**
 * 組織ツリーよりユーザーを選択する為の処理を行うライブラリ
 *
 * [依存ライブラリ]
 *   - jquery.js
 *   - jquery.ui.js
 *   - fancytree-all.min.js
 *   - wnote.js
 *   - wnote.tree.js
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
WNOTE.TREE.USER = {
    SELECT_ID: 'select-customer'
};

/** ---------------------------------------------------------------------------
 *  グローバルオブジェクト／変数
 *  -------------------------------------------------------------------------*/
/** 本アプリケーションのルートオブジェクト */
WNote.Tree.User = {};
WNote.Tree.User.Customer = {
    value: '-1',
    text : ''
};
WNote.Tree.User.Root = '';

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // ルートID設定
    WNote.Tree.User.Root
        = WNote.Tree.User._createRootId(WNote.Tree.User.Customer.value);

    // ルートノードに資産管理会社を設定
    WNote.Tree.User.changeCustomer(null);

    /** イベント登録 */
    $('#' + WNOTE.TREE.USER.SELECT_ID).on('change', WNote.Tree.User.changeCustomerHandler);

});

/** ---------------------------------------------------------------------------
 *  イベントハンドラ
 *  -------------------------------------------------------------------------*/
/** ルート選択時のイベント処理（実装） */
WNote.Tree.nodeActivateRoot = function(node) {
    // サーバーよりルート配下の組織を取得
    var result = WNote.Tree.getChildren(
        '/api/master/admin/api-organizations/root',
        {
            'customer_id': WNote.Tree.User.Customer.value
        }
    );

    // 結果データよりノードを展開する
    if (result) {
        WNote.Tree.User.expand(WNote.Tree.node(node.key), result);
    }

    // ハンドラーを呼び出す
    WNote.Tree.User.nodeActivateRootHandler(node);
}
/** ルート選択時のイベントハンドラー（利用側で実装） */
WNote.Tree.User.nodeActivateRootHandler = function(node) {}

/** ブランチ（組織）選択時のイベント処理（実装） */
WNote.Tree.nodeActivateBranch = function(node) {
    // サーバーより配下の組織とユーザーを取得
    var result = WNote.Tree.getChildren(
        '/api/master/admin/api-organizations/children-with-users',
        {
            'organization_id': node.data.organization_id
        }
    );

    // 結果データよりノードを展開する
    if (result) {
        WNote.Tree.User.expand(WNote.Tree.node(node.key), result);
    }

    // ハンドラーを呼び出す
    WNote.Tree.User.nodeActivateBranchHandler(node);
}
/** ブランチ選択時のイベントハンドラー（利用側で実装） */
WNote.Tree.User.nodeActivateBranchHandler = function(node) {}

/** アイテム（ユーザー）選択時のイベント処理（実装） */
WNote.Tree.nodeActivateValue = function(node) {
    // ハンドラーを呼び出す
    WNote.Tree.User.nodeActivateValueHandler(node);
}
/** ブランチ選択時のイベントハンドラー（利用側で実装） */
WNote.Tree.User.nodeActivateValueHandler = function(node) {}

/** ---------------------------------------------------------------------------
 *  イベント処理（会社選択）
 *  -------------------------------------------------------------------------*/
/** 資産管理会社変更時のイベント処理 */
WNote.Tree.User.changeCustomerHandler = function(event) {
    // ノードクリア
    WNote.Tree.root().removeChildren();

    // 資産管理会社変更
    WNote.Tree.User.changeCustomer(event);
}

/** 資産管理会社変更処理 */
WNote.Tree.User.changeCustomer = function(event) {
    // 選択会社設定
    WNote.Tree.User.Customer.text
        = $('#' + WNOTE.TREE.USER.SELECT_ID).children(':selected').text();
    WNote.Tree.User.Customer.value
        = $('#' + WNOTE.TREE.USER.SELECT_ID).children(':selected').val();

    // ルートID設定
    WNote.Tree.User.Root
        = WNote.Tree.User._createRootId(WNote.Tree.User.Customer.value);

    // ルートノードを追加
    WNote.Tree.root().addNode(WNote.Tree.createNode(
        WNote.Tree.User.Customer.text,
        WNote.Tree.User.Root,
        WNOTE.TREE.TYPES.ROOT,
        {},   // data
        true, // folder
        null  // childNodes
    ));

    // ツリービューのルート選択
    WNote.Tree.expand(WNote.Tree.User.Root);

    // ハンドラーを呼び出す
    WNote.Tree.User.afterChangeCustomerHandler(event);
}
/** 資産管理会社変更時のイベントハンドラー（利用側で実装） */
WNote.Tree.User.afterChangeCustomerHandler = function(event) {}

/** ---------------------------------------------------------------------------
 *  関数／メソッド（共通）
 *  -------------------------------------------------------------------------*/
/**
 * ルートノードのID（キー）を作成する（private）
 *
 * @param {number} id 資産管理会社ID
 */
WNote.Tree.User._createRootId = function(id) {
    return WNOTE.TREE.TYPES.ROOT + '_CUSTOMER_#' + id;
}
/**
 * ブランチノードのID（キー）を作成する（private）
 *
 * @param {number} id 資産管理組織ID
 */
WNote.Tree.User._createBranchId = function(id) {
    return WNOTE.TREE.TYPES.BRANCH + '_ORGANIZATION_#' + id;
}
/**
 * 値ノードのID（キー）を作成する（private）
 *
 * @param {number} id ユーザーID
 */
WNote.Tree.User._createValueId = function(id) {
    return WNOTE.TREE.TYPES.VALUE + '_UESR_#' + id;
}

/**
 * サーバーレスポンスデータよりノードを展開する
 *
 * @param {object} node FancytreeNodeオブジェクト（展開するノード）
 * @param {object} result サーバーレスポンスデータ
 */
WNote.Tree.User.expand = function(node, result) {
    // 子孫を削除
    node.removeChildren();

    // 結果データより直下の組織ノードとその配下の組織・ユーザーノードを追加
    $(result.organizations).each(function(index, item) {
        // 配下の組織配列の作成
        var children = [];
        $(item['children']).each(function(i, child) {
            children.push(WNote.Tree.User.createBranchNode(child, null));
        });

        // 配下のユーザー配列の作成
        var users    = [];
        $(item['users']).each(function(i, user) {
            users.push(WNote.Tree.User.createValueNode(user));
        });

        $.merge(children, users);
        children = children.length == 0 ? null : children;

        // 直下の組織とその配下の組織・ユーザーをノードに追加
        node.addNode(WNote.Tree.User.createBranchNode(item, children));
    });

    // 直下のユーザーノードを追加
    $(result.users).each(function(index, item) {
        // 直下の組織とその配下の組織・ユーザーをノードに追加
        node.addNode(WNote.Tree.User.createValueNode(item));
    });
}

/**
 * サーバーレスポンスデータの組織データよりノードを作成する
 *
 * @param {object} organization サーバーレスポンスデータの組織データ
 * @param {object} children 子の組織データ配列
 * @param {object} ノードオブジェクト
 */
WNote.Tree.User.createBranchNode = function(organization, children) {
    return WNote.Tree.createNode(
        organization['ancestor_name'],
        organization['id'],
        WNOTE.TREE.TYPES.BRANCH,
        {
            'organization_id': organization['descendant']
        },
        true,    // folder
        children
    );
}

/**
 * サーバーレスポンスデータのユーザーデータよりノードを作成する
 *
 * @param {object} user サーバーレスポンスデータのユーザーデータ
 */
WNote.Tree.User.createValueNode = function(user) {
    return WNote.Tree.createNode(
        user['sname'] + ' ' + user['fname'],
        user['id'],
        WNOTE.TREE.TYPES.VALUE,
        {
            'user_id': user['id']
        },
        false,    // folder
        null
    );
}
