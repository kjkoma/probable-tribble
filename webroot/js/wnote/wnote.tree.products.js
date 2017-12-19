/**
 * 分類ツリーより製品を選択する為の処理を行うライブラリ
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
WNOTE.TREE.PRODUCT = {};

/** ---------------------------------------------------------------------------
 *  グローバルオブジェクト／変数
 *  -------------------------------------------------------------------------*/
/** 本アプリケーションのルートオブジェクト */
WNote.Tree.Product = {};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // ルートノードにカテゴリを作成
    WNote.Tree.Product.createCategories();

});

/** ---------------------------------------------------------------------------
 *  イベントハンドラ
 *  -------------------------------------------------------------------------*/
/** ルート選択時のイベント処理（実装） */
WNote.Tree.nodeActivateRoot = function(node) {

    if (node.data.category_id) {
        // サーバーよりルート配下の分類を取得
        var result = WNote.Tree.getChildren(
            '/api/master/admin/api-classifications/root',
            {
                'category_id': node.data.category_id
            }
        );

        // 結果データよりノードを展開する
        if (result) {
            WNote.Tree.Product.expand(WNote.Tree.node(node.key), result);
        }
    }

    // ハンドラーを呼び出す
    WNote.Tree.Product.nodeActivateRootHandler(node);
}
/** ルート選択時のイベントハンドラー（利用側で実装） */
WNote.Tree.Product.nodeActivateRootHandler = function(node) {}

/** ブランチ（分類）選択時のイベント処理（実装） */
WNote.Tree.nodeActivateBranch = function(node) {
    // サーバーより配下の分類と製品を取得
    var result = WNote.Tree.getChildren(
        '/api/master/admin/api-classifications/children-with-products',
        {
            'classification_id': node.data.classification_id
        }
    );

    // 結果データよりノードを展開する
    if (result) {
        WNote.Tree.Product.expand(WNote.Tree.node(node.key), result);
    }

    // ハンドラーを呼び出す
    WNote.Tree.Product.nodeActivateBranchHandler(node);
}
/** ブランチ選択時のイベントハンドラー（利用側で実装） */
WNote.Tree.Product.nodeActivateBranchHandler = function(node) {}

/** アイテム（製品）選択時のイベント処理（実装） */
WNote.Tree.nodeActivateValue = function(node) {
    // ハンドラーを呼び出す
    WNote.Tree.Product.nodeActivateValueHandler(node);
}
/** ブランチ選択時のイベントハンドラー（利用側で実装） */
WNote.Tree.Product.nodeActivateValueHandler = function(node) {}

/** ---------------------------------------------------------------------------
 *  関数／メソッド（共通）
 *  -------------------------------------------------------------------------*/
/**
 * ルートノードのID（キー）を作成する（private）
 *
 * @param {number} id 資産管理会社ID
 */
WNote.Tree.Product._createRootId = function(id) {
    return WNOTE.TREE.TYPES.ROOT + '_CATEGORY_#' + id;
}
/**
 * ブランチノードのID（キー）を作成する（private）
 *
 * @param {number} id 分類ID
 */
WNote.Tree.Product._createBranchId = function(id) {
    return WNOTE.TREE.TYPES.BRANCH + '_CLASSIFICATION_#' + id;
}
/**
 * 値ノードのID（キー）を作成する（private）
 *
 * @param {number} id 製品ID
 */
WNote.Tree.Product._createValueId = function(id) {
    return WNOTE.TREE.TYPES.VALUE + '_PRODUCT_#' + id;
}

/**
 * サーバーレスポンスデータよりノードを展開する
 *
 * @param {object} node FancytreeNodeオブジェクト（展開するノード）
 * @param {object} result サーバーレスポンスデータ
 */
WNote.Tree.Product.expand = function(node, result) {
    // 子孫を削除
    node.removeChildren();

    // 結果データより直下の分類ノードとその配下の分類・製品ノードを追加
    $(result.classifications).each(function(index, item) {
        // 配下の分類配列の作成
        var children = [];
        $(item['children']).each(function(i, child) {
            children.push(WNote.Tree.Product.createBranchNode(child, null));
        });

        // 配下の製品配列の作成
        var products = [];
        $(item['products']).each(function(i, product) {
            products.push(WNote.Tree.Product.createValueNode(product));
        });

        $.merge(children, products);
        children = children.length == 0 ? null : children;

        // 直下の分類とその配下の分類・製品をノードに追加
        node.addNode(WNote.Tree.Product.createBranchNode(item, children));
    });

    // 直下の製品ノードを追加
    $(result.products).each(function(index, item) {
        // 直下の分類とその配下の分類・製品をノードに追加
        node.addNode(WNote.Tree.Product.createValueNode(item));
    });
}

/**
 * サーバーレスポンスデータの分類データよりノードを作成する
 *
 * @param {object} classification サーバーレスポンスデータの分類データ
 * @param {object} children 子の分類データ配列
 * @param {object} ノードオブジェクト
 */
WNote.Tree.Product.createBranchNode = function(classification, children) {
    return WNote.Tree.createNode(
        classification['ancestor_name'],
        classification['id'],
        WNOTE.TREE.TYPES.BRANCH,
        {
            'classification_id': classification['descendant']
        },
        true,    // folder
        children
    );
}

/**
 * サーバーレスポンスデータの製品データよりノードを作成する
 *
 * @param {object} product サーバーレスポンスデータの製品データ
 */
WNote.Tree.Product.createValueNode = function(product) {
    return WNote.Tree.createNode(
        product['kname'],
        product['id'],
        WNOTE.TREE.TYPES.VALUE,
        {
            'product_id': product['id']
        },
        false,    // folder
        null
    );
}

/**
 * カテゴリのノードを作成する
 *
 */
WNote.Tree.Product.createCategories = function() {
    // サーバーよりカテゴリを取得
    var result = WNote.ajaxValidateSend('/api/master/admin/api-categories/categories', 'POST', []);
    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('カテゴリが存在しないか、取得できません。カテゴリが登録されていることを確認してください。');
        return ;
    }

    // カテゴリノード（ルートノード）を追加
    var first = undefined;
    $(result.categories).each(function(index, item) {
        var key = WNote.Tree.Product._createRootId(item['id']);
        WNote.Tree.root().addNode(WNote.Tree.createNode(
            item['kname'],
            key,
            WNOTE.TREE.TYPES.ROOT,
            {
                category_id: item['id']
            },
            true, // folder
            null  // childNodes
        ));

        if (first) WNote.Tree.expand(key);
        first = first || key;
    });

    // 最初のカテゴリを展開
    if (first) WNote.Tree.expand(first);
}

