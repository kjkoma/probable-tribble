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
const WNOTE_ASSET_USER = {};

/** ---------------------------------------------------------------------------
 *  変数
 *  -------------------------------------------------------------------------*/
/** 本ライブラリのオブジェクト */
WNote.Asset = WNote.Asset || {};
WNote.Asset.User = {};

/** datatableのインスタンス */
WNote.Asset.User.datatable = null;

/** ---------------------------------------------------------------------------
 *  初期処理
 *  -------------------------------------------------------------------------*/
$(function() {

    // 利用者一覧テーブル（datatable）初期化
    WNote.Asset.User.datatable = $('#elemAssetUser-datatable').DataTable({
        paging    : false,
        scrollY   : 800,
        scrollX   : false,
        searching : false,
        ordering  : false,
        columns   : [
            { data: 'useage_sts_name'  , width: '16%'  },
            { data: 'user_name'        , width: '18%'  },
            { data: 'admin_user_name'  , width: '18%'  },
            { data: 'useage_type_name' , width: '16%'  },
            { data: 'start_date'       , width: '16%'  },
            { data: 'end_date'         , width: '16%'  }
        ],
        data      : []
    });

    /* 表示資産変更イベント登録 */
    WNote.Asset.afterChangeAssetRegister('user', WNote.Asset.User.show);

});

/** ---------------------------------------------------------------------------
 *  タブ選択時処理（wnote.asset.js宣言の実装）
 *  -------------------------------------------------------------------------*/
/**
 * タブ選択時処理
 */
WNote.Asset.selectUserHandler = function() {
}

/** ---------------------------------------------------------------------------
 *  利用者履歴表示処理
 *  -------------------------------------------------------------------------*/
/**
 * 利用者履歴を表示する
 */
WNote.Asset.User.show = function() {
    WNote.Asset.User.getUsers(WNote.Asset.selectData.id);
}

/** ---------------------------------------------------------------------------
 *  イベント処理（表示）
 *  -------------------------------------------------------------------------*/
/**
 * 利用者一覧取得
 *
 * @param {string} assetId 資産ID
 */
WNote.Asset.User.getUsers = function(assetId) {
    WNote.ajaxFailureMessage = '利用者履歴の読込に失敗しました。';
    WNote.ajaxSendBasic('/api/asset/api-asset-users/users', 'POST',
        { 'asset_id': assetId },
        false,
        WNote.Asset.User.getUsersSuccess
    );
}

/**
 * 利用者一覧成功時のハンドラの実装
 *
 * @param {object} data レスポンスデータ
 */
WNote.Asset.User.getUsersSuccess = function(data) {
    if (data && data.data) {
        WNote.Asset.User.datatable
            .clear()
            .rows.add(data.data.param.users)
            .draw();
    }
}






