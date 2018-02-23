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
    ROW : {
        SEARCH: '#home-search-result',
        BACK  : '#grid-row-back'
    }
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    WNote.registerEvent('click', 'back', MyPage.showSearch);

});

/** ---------------------------------------------------------------------------
 *  イベント処理（検索表示）
 *  -------------------------------------------------------------------------*/
/**
 * 検索表示ボタンクリックイベントのハンドラの実装
 */
MyPage.showSearch = function() {
    $(MYPAGE.ROW.SEARCH).removeClass('hidden');
    WNote.Asset.hideWidget();
    $(MYPAGE.ROW.BACK).addClass('hidden');
}

/** ---------------------------------------------------------------------------
 *  イベント処理（検索データ選択）
 *  -------------------------------------------------------------------------*/
/**
 * 検索データ選択イベントのハンドラの実装
 * 
 * @param {string} assetId 資産ID
 */
MyPage.selectedRowHandler = function(assetId) {
    $(MYPAGE.ROW.BACK).removeClass('hidden');
    WNote.Asset.showWidget(assetId);
    $(MYPAGE.ROW.SEARCH).addClass('hidden');
}
