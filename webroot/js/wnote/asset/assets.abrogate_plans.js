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
    FORM_DOWNLOAD: 'form-download'
};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** datatableのインスタンス */
MyPage.datatable = null;


/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 廃棄予定一覧テーブル（datatable）初期化
    MyPage.datatable = $('#abrogate-plans-datatable').DataTable({
        paging    : false,
        scrollY   : 620,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'classification_name' , width: '10%' },
            { data: 'maker_name'          , width: '8%'  },
            { data: 'product_name'        , width: '16%' },
            { data: 'serial_no'           , width: '10%' },
            { data: 'asset_no'            , width: '8%'  },
            { data: 'repair_count'        , width: '8%'  },
            { data: 'abrogate_date'       , width: '8%'  },
            { data: 'abrogate_suser_name' , width: '8%'  },
            { data: 'abrogate_reason'     , width: '24%' }
        ],
        data      : []
    });

    /** データテーブルイベント登録 */
    $('#abrogate-plans-datatable tbody').on('click', 'tr', MyPage.selectedRowHandler);

    /** 各種操作イベント登録 */
    WNote.registerEvent('click', 'all'     , MyPage.abrogateAllHandler);
    WNote.registerEvent('click', 'selected', MyPage.abrogateSelectedHandler);
    WNote.registerEvent('click', 'download', MyPage.download);

    // 廃棄予定一覧を表示する
    MyPage.showTable();

});

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 廃棄予定一覧取得
 *
 * @param {object} data 検索条件
 */
MyPage.getAbrogatePlans = function(data) {
    // 廃棄予定一覧の取得
    var result = WNote.ajaxValidateSend('/api/asset/api-assets/abrogate-plans', 'POST', {});

    if (!result) {
        WNote.log(result);
        WNote.showErrorMessage('エラーが発生した為、資産一覧が取得できませんでした。');
        return null;
    }

    return result;
}

/**
 * 廃棄予定一覧表示
 *
 * @param {object} data 検索条件
 */
MyPage.showTable = function(data) {
    var result = MyPage.getAbrogatePlans(data);
    if (result) {
        MyPage.datatable
            .clear()
            .rows.add(result.assets)
            .draw();
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（ダウンロード）
 *  -------------------------------------------------------------------------*/
/**
 * ダウンロードボタンクリックイベントのハンドラの実装
 */
MyPage.download = function() {
    $('#' + MYPAGE.FORM_DOWNLOAD).submit();
}

/** ---------------------------------------------------------------------------
 *  イベント処理（廃棄予定選択）
 *  -------------------------------------------------------------------------*/
/**
 * 廃棄予定選択イベントのハンドラの実装
 */
MyPage.selectedRowHandler = function() {
    var selected = MyPage.datatable.row( this ).data();
    if (selected) {
        WNote.Util.All.switchHighlightDataTableRow($(this), MyPage.datatable);
    }
}

/** ---------------------------------------------------------------------------
 *  イベント処理（保存）
 *  -------------------------------------------------------------------------*/
/**
 * すべて廃棄するボタンクリック時のイベントハンドラ
 *
 */
MyPage.abrogateAllHandler = function() {
    WNote.showConfirmMessage('表示中の廃棄予定をすべて廃棄に更新します。更新を行ってよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.abrogateAll;
}

/**
 * 選択行を廃棄するボタンクリック時のイベントハンドラ
 *
 */
MyPage.abrogateSelectedHandler = function() {
    WNote.showConfirmMessage('選択されている行の廃棄予定をすべて廃棄に更新します。更新を行ってよろしいですか？');
    WNote.showConfirmYesHandler = MyPage.abrogateSelected;
}

/**
 * すべて廃棄するボタンクリックイベントのハンドラの実装
 */
MyPage.abrogateAll = function() {
    MyPage.save(MyPage.datatable.row().data());
}

/**
 * 選択行を廃棄するボタンクリックイベントのハンドラの実装
 */
MyPage.abrogateSelected = function() {
    MyPage.save(MyPage.datatable.rows('tr.selected').data());
}

/**
 * 更新ボタンクリックイベントのハンドラの実装
 *
 * @param {object} rows 廃棄予定の行データ
 * @param {string} saveType 保存タイプ（all: すべて、selected: 行選択）
 */
MyPage.save = function(rows) {

    // 送信データ作成
    var data = MyPage.createSaveData(rows);

    // 送信データの検証
    if (!WNote.ajaxValidateWarning(data, MyPage.saveValidate(data))) {
        return;
    }

    // データ保存
    WNote.ajaxFailureMessage = '廃棄に失敗しました。再度廃棄してもエラーとなる場合、管理者にお問い合わせください。';
    WNote.ajaxSendBasic('/api/asset/api-assets/abrogate', 'POST',
        {
            'assets': data
        },
        true,
        MyPage.saveSuccess
    );
}

/**
 * 更新データを作成する
 *
 * @param {object} rows 廃棄予定の行データ
 * @return {object} 更新データ
 */
MyPage.createSaveData = function(rows) {
    var data = [];

    $(rows).each(function(i, row) {
        data.push(row);
    });

    return data;
}

/**
 * 更新成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
MyPage.saveSuccess = function(data) {
WNote.log(data); // debug
    WNote.ajaxSuccessHandler(data, '廃棄に成功しました。一覧を再表示します。');
    MyPage.showTable();
}

/**
 * 廃棄更新時の検証を行う
 *
 * @param {object} data 送信データ
 * @result {object} 検証結果セット（result: true/false, msg: エラーメッセージ）
 */
MyPage.saveValidate = function(data) {
    // 選択していないケースの検証
    if (!data || data.length == 0) {
        return WNote.Form.validateResultSet('廃棄対象の廃棄予定が選択されていないか、存在していません。');
    }

    // 検証結果正常で返す
    return WNote.Form.validateResultSet(null);
}


