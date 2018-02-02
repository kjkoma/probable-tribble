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
/*
 */
/** ---------------------------------------------------------------------------
 *  定数
 *  -------------------------------------------------------------------------*/
const WNOTE_ASSET_REPAIR = {};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Asset = WNote.Asset || {};
WNote.Asset.Repair = {};

/** datatableのインスタンス */
WNote.Asset.Repair.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 修理一覧テーブル（datatable）初期化
    WNote.Asset.Repair.datatable = $('#elemAssetRepair-datatable').DataTable({
        paging    : false,
        scrollY   : 800,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'repair_sts_name' , width: '8%'  },
            { data: 'start_date'      , width: '8%'  },
            { data: 'end_date'        , width: '10%' },
            { data: 'trouble_kbn_name', width: '10%'  },
            { data: 'trouble_reason'  , width: '64%'  }
        ],
        data      : []
    });

    /* 表示資産変更イベント登録 */
    WNote.Asset.afterChangeAssetRegister('repair', WNote.Asset.Repair.show);

});

/** ---------------------------------------------------------------------------
 *  タブ選択時処理（wnote.asset.js宣言の実装）
 *  -------------------------------------------------------------------------*/
/**
 * タブ選択時処理
 */
WNote.Asset.selectRepairHandler = function() {
}

/** ---------------------------------------------------------------------------
 *  修理履歴表示処理
 *  -------------------------------------------------------------------------*/
/**
 * 修理履歴を表示する
 */
WNote.Asset.Repair.show = function() {
    WNote.Asset.Repair.getRepairs(WNote.Asset.selectData.id);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 修理一覧取得
 *
 * @param {string} assetId 資産ID
 */
WNote.Asset.Repair.getRepairs = function(assetId) {
    WNote.ajaxFailureMessage = '修理履歴の読込に失敗しました。';
    WNote.ajaxSendBasic('/api/repair/api-repairs/list_by_asset_id', 'POST',
        { 'asset_id': assetId },
        false,
        WNote.Asset.Repair.getRepairsSuccess
    );
}

/**
 * 修理一覧成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.Repair.getRepairsSuccess = function(data) {
    if (data && data.data) {
        WNote.Asset.Repair.datatable
            .clear()
            .rows.add(data.data.param.repairs)
            .draw();
    }
}
