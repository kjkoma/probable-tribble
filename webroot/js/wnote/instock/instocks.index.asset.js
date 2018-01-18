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
const MYPAGE_ASSET = {
    FORM_KEY : 'form-instock-asset',
    PREFIX   : 'asset_',
    WIDGET   : '#wid-id-asset',
    SEARCH   : '#asset_search_key'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 選択中の資産データ */
MyPage.Asset = {};
MyPage.Asset.selectData = {
    selected: {}
};

/** datatableのインスタンス */
MyPage.Asset.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 入庫予定一覧テーブル（datatable）初期化
    MyPage.Asset.datatable = $('#plan-asset-datatable').DataTable({
        paging    : false,
        scrollY   : 260,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'instock_kbn_name'    , width: '8%'  },
            { data: 'plan_date'           , width: '8%'  },
            { data: 'name'                , width: '16%' },
            { data: 'asset_no'            , width: '8%'  },
            { data: 'serial_no'           , width: '8%'  },
            { data: 'classification_name' , width: '10%' },
            { data: 'product_name'        , width: '12%' },
            { data: 'remarks'             , width: '30%' }
        ],
        data: []
    });

    /** シリアル入力テキストのEnterキー押下イベント／変更イベント登録 */
    $(MYPAGE_ASSET.SEARCH).on('keypress', function (e) { if(e.which === 13){ MyPage.Asset.inputSearchKeyHandler(e); }});

    /** データテーブルイベント登録 */
    $('#plan-asset-datatable tbody').on('click', 'tr', MyPage.Asset.selectedPlanHandler);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'asset-save', MyPage.Asset.save);
});

/** ---------------------------------------------------------------------------
 *  入庫ライブラリ（instocks.index.js）内宣言の実装
 *  -------------------------------------------------------------------------*/
/**
 * 単品入庫選択選択（入庫ライブラリ内の宣言の実装）
 */
MyPage.selectAsset = function() {
    $(MYPAGE_ASSET.WIDGET).removeClass('hidden');
}

/**
 * 単品入庫選択解除（入庫ライブラリ内の宣言の実装）
 */
MyPage.unselectAsset = function() {
    $(MYPAGE_ASSET.WIDGET).addClass('hidden');
}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示 - 入庫予定詳細一覧）
 *  -------------------------------------------------------------------------*/
/**
 * 入庫予定詳細一覧取得
 *
 * @param {string} cond 検索キー
 */
MyPage.Asset.getPlans = function(cond) {
    // 入庫予定詳細一覧の取得
    var result = WNote.ajaxValidateSend('/api/instock/api-instock-plan-details/details-asset', 'POST',　{
        'cond': cond
    });

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、入庫予定の一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 入庫予定詳細一覧表示
 *
 * @param {string} cond 検索キー
 */
MyPage.Asset.showPlans = function(cond) {
    MyPage.Asset.selectData.selected = {};

    var result = MyPage.Asset.getPlans(cond);
    if (result) {
        MyPage.Asset.datatable
            .clear()
            .rows.add(result.details)
            .draw();
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（予定選択）
 *  -------------------------------------------------------------------------*/
/**
 * 入庫予定詳細一覧の予定選択イベントハンドラー
 *
 */
MyPage.Asset.selectedPlanHandler = function() {
    var selected = MyPage.Asset.datatable.row( this ).data();
    if (selected) {
        MyPage.Asset.selectData.selected = selected;
        WNote.Util.All.highlightDataTableRow($(this), MyPage.Asset.datatable);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（予定選択）
 *  -------------------------------------------------------------------------*/
/**
 * シリアル番号/資産管理番号入力テキストEnterキー押下イベントハンドラー
 *
 * @param {object} event イベント
 */
MyPage.Asset.inputSearchKeyHandler = function(event) {
    var value = $(event.target).val().trim();
    if (value == '') return;

    MyPage.Asset.showPlans(value);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（クリア処理）
 *  -------------------------------------------------------------------------*/
/**
 * 本画面のクリア処理を行う
 */
MyPage.Asset.clear = function() {
    MyPage.Asset.selectData.selected = {};
    MyPage.Asset.datatable.clear().draw();
    $(MYPAGE_ASSET.SEARCH).val('');
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * 入庫データを登録する
 *
 */
MyPage.Asset.save = function() {

    // 送信データ作成
    var data = MyPage.createSaveData();
    data.instock.instock_plan_id        = MyPage.Asset.selectData.selected.instock_plan_id;
    data.instock.instock_plan_detail_id = MyPage.Asset.selectData.selected.id;
    data.asset_id                       = MyPage.Asset.selectData.selected.asset_id;

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.Asset.saveValidator(data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '入力された内容の保存に失敗しました。再度保存してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/instock/api-instocks/add-new', 'POST',
        data,
        true,
        MyPage.Asset.saveSuccess
    );
}

/**
 * 保存ボタンクリック成功時のハンドラの実装（追加時）
 *
 * @param {object} data レスポンスデータ
 */
MyPage.Asset.saveSuccess = function(data) {
    WNote.ajaxSuccessHandler(data, '入庫情報をを登録しました。');
    MyPage.clearValidation();
    MyPage.Asset.clear();
}

/** ---------------------------------------------------------------------------
 *  バリデータ処理
 *  -------------------------------------------------------------------------*/
/**
 * 保存処理時のバリデータ
 *
 * @param {object} data 送信データ
 */
MyPage.Asset.saveValidator = function(data) {

    // 選択データを確認する
    if (!MyPage.Asset.selectData.selected || !MyPage.Asset.selectData.selected.id) {
        return WNote.Form.validateResultSet('出庫対象の予定が選択されていません。予定を選択してください。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}

