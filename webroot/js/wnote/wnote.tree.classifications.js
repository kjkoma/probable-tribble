/**
 * カテゴリ・分類ツリーより分類を選択する為の処理を行うライブラリ
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
WNOTE.TREE.CLASSIFICATION = {};

/** ---------------------------------------------------------------------------
 *  グローバルオブジェクト／変数
 *  -------------------------------------------------------------------------*/
/** 本アプリケーションのルートオブジェクト */
WNote.Tree.Classification = {};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // ルートノードにカテゴリを作成
    WNote.Tree.Classification.createCategories();

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
            WNote.Tree.Classification.expand(WNote.Tree.node(node.key), result);
        }
    }

    // ハンドラーを呼び出す
    WNote.Tree.Classification.nodeActivateRootHandler(node);
}
/** ルート選択時のイベントハンドラー（利用側で実装） */
WNote.Tree.Classification.nodeActivateRootHandler = function(node) {}

/** アイテム選択時のイベント処理（実装） */
WNote.Tree.nodeActivateItem = function(node) {
    // サーバーより配下の分類を取得
    var result = WNote.Tree.getChildren(
        '/api/master/admin/api-classifications/children',
        {
            'classification_id': node.data.classification_id
        }
    );

    // 結果データよりノードを展開する
    if (result) {
        WNote.Tree.Classification.expand(WNote.Tree.node(node.key), result);
    }

    // ハンドラーを呼び出す
    WNote.Tree.Classification.nodeActivateItemHandler(node);
}
/** アイテム選択時のイベントハンドラー（利用側で実装） */
WNote.Tree.Classification.nodeActivateItemHandler = function(node) {}

/** ---------------------------------------------------------------------------
 *  関数／メソッド（共通）
 *  -------------------------------------------------------------------------*/
/**
 * ルートノードのID（キー）を作成する（private）
 *
 * @param {number} id カテゴリID
 */
WNote.Tree.Classification._createRootId = function(id) {
    return WNOTE.TREE.TYPES.ROOT + '_CATEGORY_#' + id;
}
/**
 * アイテムノードのID（キー）を作成する（private）
 *
 * @param {number} id 分類ID
 */
WNote.Tree.Classification._createItemId = function(id) {
    return WNOTE.TREE.TYPES.ITEM + '_CLASSIFICATION_#' + id;
}

/**
 * サーバーレスポンスデータよりノードを展開する
 *
 * @param {object} node FancytreeNodeオブジェクト（展開するノード）
 * @param {object} result サーバーレスポンスデータ
 */
WNote.Tree.Classification.expand = function(node, result) {
    // 子孫を削除
    node.removeChildren();

    // 結果データよりノードを追加
    $(result.classifications).each(function(index, item) {
        var children = null;
        $(item['children']).each(function(i, child) {
            children = children || [];
            children.push(WNote.Tree.Classification.createNode(child, null));
        });
        node.addNode(WNote.Tree.Classification.createNode(item, children));
    });
}

/**
 * サーバーレスポンスデータの分類データよりノードを作成する
 *
 * @param {object} result サーバーレスポンスデータの分類データ
 * @param {object} children 子の分類データ配列
 * @param {object} ノードオブジェクト
 */
WNote.Tree.Classification.createNode = function(classification, children) {
    return WNote.Tree.createNode(
        classification['ancestor_name'],
        WNote.Tree.Classification._createItemId(classification['id']),
        WNOTE.TREE.TYPES.ITEM,
        {
            'classification_id': classification['descendant']
        },
        true,    // folder
        children
    );
}

/**
 * カテゴリのノードを作成する
 *
 */
WNote.Tree.Classification.createCategories = function() {
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
        var key = WNote.Tree.Classification._createRootId(item['id']);
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

