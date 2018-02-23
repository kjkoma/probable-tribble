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
const WNOTE_ASSET_RENTAL = {};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Asset = WNote.Asset || {};
WNote.Asset.Rental = {};

/** datatableのインスタンス */
WNote.Asset.Rental.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 貸出一覧テーブル（datatable）初期化
    WNote.Asset.Rental.datatable = $('#elemAssetRental-datatable').DataTable({
        paging    : false,
        scrollY   : 800,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'rental_sts_name'  , width: '12%'  },
            { data: 'user_name'        , width: '12%'  },
            { data: 'admin_user_name'  , width: '13%'  },
            { data: 'rental_date'      , width: '12%'  },
            { data: 'rental_suser_name', width: '13%'  },
            { data: 'back_date'        , width: '12%'  },
            { data: 'back_user_name'   , width: '13%'  },
            { data: 'back_suser_name'  , width: '13%'  }
        ],
        data      : []
    });

    /* 表示資産変更イベント登録 */
    WNote.Asset.afterChangeAssetRegister('rental', WNote.Asset.Rental.show);

});

/** ---------------------------------------------------------------------------
 *  タブ選択時処理（wnote.asset.js宣言の実装）
 *  -------------------------------------------------------------------------*/
/**
 * タブ選択時処理
 */
WNote.Asset.selectRentalHandler = function() {
}

/** ---------------------------------------------------------------------------
 *  貸出・返却履歴表示処理
 *  -------------------------------------------------------------------------*/
/**
 * 貸出・返却履歴を表示する
 */
WNote.Asset.Rental.show = function() {
    WNote.Asset.Rental.getRentals(WNote.Asset.selectData.id);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 貸出一覧取得
 *
 * @param {string} assetId 資産ID
 */
WNote.Asset.Rental.getRentals = function(assetId) {
    WNote.ajaxFailureMessage = '貸出・返却履歴の読込に失敗しました。';
    WNote.ajaxSendBasic('/api/rental/api-rentals/list_by_asset_id', 'POST',
        { 'asset_id': assetId },
        false,
        WNote.Asset.Rental.getRentalsSuccess
    );
}

/**
 * 貸出一覧成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.Rental.getRentalsSuccess = function(data) {
    if (data && data.data) {
        WNote.Asset.Rental.datatable
            .clear()
            .rows.add(data.data.param.rentals)
            .draw();
    }
}



