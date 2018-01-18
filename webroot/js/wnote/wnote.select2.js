/**
 * Select2コンボボックス用ライブラリ
 * 
 * [依存ライブラリ]
 *   - jquery.js
 *   - jquery.ui.js
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
 *  グローバルオブジェクト／変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのルートオブジェクト */
WNote.Select2 = {};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {
});

/** ---------------------------------------------------------------------------
 *  汎用関数
 *  -------------------------------------------------------------------------*/
/**
 * 分類選択用
 
 * @param {string} id 要素ID
 * @param {url} url 送信URL
 * @param {object} data 送信データオブジェクト（不要時はnull）
 * @param {object} 受信データの処理用ハンドラー
 * @param {boolean} enableClear クリア可否
 * @param {string} placeHolder プレースホルダ文字列
 */
WNote.Select2.select = function(id, url, data, handler, enableClear, placeHolder)
{
    var sendData = data || function(params) { return params; };
    $(id).select2({
        placeholder: placeHolder,
        allowClear: enableClear,
        ajax: WNote.ajaxAddSelect2Options({
            url: url,
            data: sendData,
            processResults: handler
        })
    });
}

/** ---------------------------------------------------------------------------
 *  Select2 選択Box
 *  -------------------------------------------------------------------------*/
/**
 * 認証ユーザー(Susers)選択用
 *
 * @param {string} id 要素ID
 * @param {object} dataPram (指定不要時はnull)
 * @param {boolean} enableClear クリア可否
 * @param {string} placeHolder プレースホルダ文字列
 */
WNote.Select2.sUser = function(id, dataPram, enableClear, placeHolder)
{
    var data = (dataPram) ? dataPram : null;
    WNote.Select2.select(
        id, '/api/master/system/api-susers/find-list', data, 
        WNote.Select2.sUserHandler, enableClear, placeHolder);
}
WNote.Select2.sUserHandler = function(data) {
    return { results: (data.data.param) ? data.data.param.susers : [] };
}

/**
 * 組織選択用
 *
 * @param {string} id 要素ID
 * @param {object} dataPram (指定不要時はnull)
 * @param {boolean} enableClear クリア可否
 * @param {string} placeHolder プレースホルダ文字列
 */
WNote.Select2.organization = function(id, dataPram, enableClear, placeHolder)
{
    var data = (dataPram) ? dataPram : null;
    WNote.Select2.select(
        id, '/api/master/admin/api-organizations/find-list', data, 
        WNote.Select2.organizationHandler, enableClear, placeHolder);
}
WNote.Select2.organizationHandler = function(data) {
    return { results: (data.data.param) ? data.data.param.organizations : [] };
}

/**
 * ユーザー選択用
 *
 * @param {string} id 要素ID
 * @param {string} organization_idの要素ID (指定不要時はnull)
 * @param {boolean} enableClear クリア可否
 * @param {string} placeHolder プレースホルダ文字列
 */
WNote.Select2.user = function(id, organization_id, enableClear, placeHolder)
{
    var data = (organization_id) ? WNote.Select2.userData(organization_id) : null;
    WNote.Select2.select(
        id, '/api/master/admin/api-users/find-list', data, 
        WNote.Select2.userHandler, enableClear, placeHolder);
}
WNote.Select2.userData = function(organization_id) {
    return function(params) { params.organization_id = $(organization_id).val(); return params; };
}
WNote.Select2.userHandler = function(data) {
    return { results: (data.data.param) ? data.data.param.users : [] };
}

/**
 * 分類選択用
 *
 * @param {string} id 要素ID
 * @param {string} category_idの要素ID (指定不要時はnull)
 * @param {boolean} enableClear クリア可否
 * @param {string} placeHolder プレースホルダ文字列
 */
WNote.Select2.classification = function(id, category_id, enableClear, placeHolder)
{
    var data = (category_id) ? WNote.Select2.classificationData(category_id) : null;
    WNote.Select2.select(
        id, '/api/master/admin/api-classifications/find-list', data, 
        WNote.Select2.classificationHandler, enableClear, placeHolder);
}
WNote.Select2.classificationData = function(category_id) {
    return function(params) { params.category_id = $(category_id).val(); return params; };
}
WNote.Select2.classificationHandler = function(data) {
    return { results: (data.data.param) ? data.data.param.classifications : [] };
}

/**
 * 製品選択用
 *
 * @param {string} id 要素ID
 * @param {string} classification_idの要素ID (指定不要時はnull)
 * @param {boolean} enableClear クリア可否
 * @param {string} placeHolder プレースホルダ文字列
 */
WNote.Select2.product = function(id, classification_id, enableClear, placeHolder)
{
    var data = (classification_id) ? WNote.Select2.productData(classification_id) : null;
    WNote.Select2.select(
        id, '/api/master/general/api-products/find-list', data, 
        WNote.Select2.productHandler, enableClear, placeHolder);
}
WNote.Select2.productData = function(classification_id) {
    return function(params) { params.classification_id = $(classification_id).val(); return params; };
}
WNote.Select2.productHandler = function(data) {
    return { results: (data.data.param) ? data.data.param.products : [] };
}

/**
 * モデル選択用
 *
 * @param {string} id 要素ID
 * @param {string} product_idの要素ID (指定不要時はnull)
 * @param {boolean} enableClear クリア可否
 * @param {string} placeHolder プレースホルダ文字列
 */
WNote.Select2.model = function(id, product_id, enableClear, placeHolder)
{
    var data = (product_id) ? WNote.Select2.modelData(product_id) : null;
    WNote.Select2.select(id, '/api/master/general/api-product-models/find-list', data, 
        WNote.Select2.modelHandler, enableClear, placeHolder);
}
WNote.Select2.modelData = function(product_id) {
    return function(params) { params.product_id = $(product_id).val(); return params; };
}
WNote.Select2.modelHandler = function(data) {
    return { results: (data.data.param) ? data.data.param.models : [] };
}

/**
 * キッティングパターン選択用
 *
 * @param {string} id 要素ID
 * @param {object} params reuse_kbn/pattern_kbn/patternの要素IDオブジェクト
 * @param {boolean} enableClear クリア可否
 * @param {string} placeHolder プレースホルダ文字列
 */
WNote.Select2.kittingPattern = function(id, params, enableClear, placeHolder)
{
    var data = (params) ? WNote.Select2.kittingPatternData(params) : null;
    WNote.Select2.select(id, '/api/master/general/api-kitting-patterns/find-list', data, 
        WNote.Select2.kittingPatternHandler, enableClear, placeHolder);
}
WNote.Select2.kittingPatternData = function(sendParams) {
    return function(params) {
        if (sendParams.reuse_kbn)    { params.reuse_kbn    = $(sendParams.reuse_kbn).val();    }
        if (sendParams.pattern_kbn)  { params.pattern_kbn  = $(sendParams.pattern_kbn).val();  }
        if (sendParams.pattern_type) { params.pattern_type = $(sendParams.pattern_type).val(); }
        return params;
    };
}
WNote.Select2.kittingPatternHandler = function(data) {
    return { results: (data.data.param) ? data.data.param.patterns : [] };
}
