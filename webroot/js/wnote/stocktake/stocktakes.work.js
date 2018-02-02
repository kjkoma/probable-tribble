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
    FORM_KEY: ""
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中の棚卸データ */
MyPage.selectData = {
    stocktake: {}
};

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

});

/** ---------------------------------------------------------------------------
 *  イベント処理（棚卸選択）
 *  -------------------------------------------------------------------------*/
/**
 * サイドリスト（Element/Parts/side-list）用クリックイベントのハンドラの実装
 */
WNote.sideListClickHandler = function(event) {
    // 選択棚卸の取得
    var id = $(event.target).attr(WNOTE.DATA_ATTR.ID);

    // 文字の部分選択時はSPANタグ要素がevent.targetになっているので親要素よりIDを取得する
    if ($(event.target).prop("tagName") == "SPAN") {
        id = $(event.target).parent().attr(WNOTE.DATA_ATTR.ID);
    }

    // 選択行ハイライト
    WNote.Util.All.highlightNestableListRow(event.target);

    // 棚卸の取得
    MyPage.getStocktake(id);
}

/**
 * 棚卸取得
 *
 * @param {integer} stocktakeId 棚卸ID
 */
MyPage.getStocktake = function(stocktakeId) {
    // 棚卸データの取得
    WNote.ajaxFailureMessage = '棚卸データの読込に失敗しました。再度棚卸を選択してください。';
    WNote.ajaxSendBasic('/api/stocktake/api-stocktakes/stocktake', 'POST',
        { 'stocktake_id': stocktakeId },
        false,
        MyPage.getStocktakeSuccess
    );
}

/**
 * 棚卸取得成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.getStocktakeSuccess = function(data) {
    MyPage.selectData.stocktake = data.data.param.stocktake;
    WNote.StocktakeWork.showWidget(data.data.param.stocktake.id);
}

