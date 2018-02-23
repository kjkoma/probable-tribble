<?php
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
namespace App\Controller\Asset;

use App\Controller\AppController;

/**
 * Assets Controller
 *
 */
class AssetsController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelAssets');
        $this->_loadComponent('ModelCompanies');
        $this->_loadComponent('ModelCategories');
        $this->_loadComponent('SysModelSnames');
    }

    /**
     * 資産一覧を検索する
     *
     * @return \Cake\Http\Response|void
     */
    public function search()
    {
        $makers      = $this->ModelCompanies->makers();
        $categories  = $this->ModelCategories->valid();
        $assetType   = $this->SysModelSnames->byKey('ASSET_TYPE');
        $assetSts    = $this->SysModelSnames->byKey('ASSET_STS');
        $assetSubSts = $this->SysModelSnames->byKey('ASSET_SUB_STS');

        $this->set(compact('makers', 'categories', 'assetType', 'assetSts', 'assetSubSts'));
        $this->render();
    }

    /**
     * 資産集計画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function summary()
    {
        $makers      = $this->ModelCompanies->makers();
        $categories  = $this->ModelCategories->valid();
        $assetType   = $this->SysModelSnames->byKey('ASSET_TYPE');
        $assetSts    = $this->SysModelSnames->byKey('ASSET_STS');
        $assetSubSts = $this->SysModelSnames->byKey('ASSET_SUB_STS');

        $this->set(compact('makers', 'categories', 'assetType', 'assetSts', 'assetSubSts'));
        $this->render();
    }

    /**
     * 資産登録画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function entry()
    {
        $makers      = $this->ModelCompanies->makers();
        $categories  = $this->ModelCategories->valid();
        $assetType   = $this->SysModelSnames->byKey('ASSET_TYPE');
        $assetSts    = $this->SysModelSnames->byKey('ASSET_STS');
        $assetSubSts = $this->SysModelSnames->byKey('ASSET_SUB_STS');

        $this->set(compact('makers', 'categories', 'assetType', 'assetSts', 'assetSubSts'));
        $this->render();
    }

    /**
     * 廃棄予定一覧を検索する
     *
     * @return \Cake\Http\Response|void
     */
    public function abrogatePlans()
    {
        $this->render();
    }

    /**
     * 廃棄済一覧を検索する
     *
     * @return \Cake\Http\Response|void
     */
    public function abrogates()
    {
        $makers      = $this->ModelCompanies->makers();
        $categories  = $this->ModelCategories->valid();
        $assetType   = $this->SysModelSnames->byKey('ASSET_TYPE');
        $assetSts    = $this->SysModelSnames->byKey('ASSET_STS');
        $assetSubSts = $this->SysModelSnames->byKey('ASSET_SUB_STS');

        $this->set(compact('makers', 'categories', 'assetType', 'assetSts', 'assetSubSts'));
        $this->render();
    }

    /**
     * 資産一覧データをエクスポートする
     *
     * @return \Cake\Http\Response|void
     */
    public function download()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 資産一覧を取得
        $assets = $this->ModelAssets->search($data['cond'], true);

        // ダウンロード
        $_serialize     = ['assets'];
        $_extract   = [
            function ($row) { return $row['asset_type_name']['name']; },             // 資産タイプ名称
            function ($row) { return $row['asset_sts_name']['name']; },              // 資産状況
            function ($row) { return $row['asset_sub_sts_name']['name']; },          // 資産状況（サブ）
            function ($row) { return $row['classification']['category']['kname']; }, // カテゴリ名
            function ($row) { return $row['classification']['kname']; },             // 分類名
            function ($row) { return $row['company']['kname']; },                    // メーカー名
            function ($row) { return $row['product']['kname']; },                    // 製品名
            function ($row) { return ($row['product_model']) ? $row['product_model']['kname'] : ''; }, // モデル(型)名
            function ($row) { return $row['serial_no']; },                           // シリアル番号
            function ($row) { return $row['asset_no']; },                            // 資産管理番号
            function ($row) { return $row['first_instock_date']; },                  // 初回入庫日
            function ($row) { return $row['account_date']; },                        // 計上日(初回出庫日)
            function ($row) { return $row['abrogate_date']; },                       // 廃棄日
            function ($row) { return $row['support_limit_date']; },                  // 保守期限日
            function ($row) { return ($row['user']) ? $row['user']['organization']['kname'] : ''; },               // 利用者(組織)
            function ($row) { return ($row['user']) ? $row['user']['sname'] . ' ' . $row['user']['fname'] : ''; }, // 利用者(ユーザー)
            function ($row) { return $row['remarks']; },                             // 補足
            function ($row) { return $row['created_at']; },                          // 登録日時
            function ($row) { return $row['asset_created_suser']['kname']; },        // 登録者
            function ($row) { return $row['modified_at']; },                         // 更新日時
            function ($row) { return $row['asset_modified_suser']['kname']; },       // 更新者
        ];
        $_header    = [
            '資産タイプ', '資産状況', '資産状況(サブ)', 'カテゴリ名', '分類名', 'メーカー名', '製品名', 'モデル(型)名',
            'シリアル番号', '資産管理番号', '初回入庫日', '計上日(初回出庫日)', '廃棄日', '保守期限日', '利用者(組織)',
            '利用者(ユーザー)', '補足', '登録日時', '登録者', '更新日時', '更新者'
        ];
        $_csvEncoding = 'SJIS-win';
        $_newline     = "\r\n";
        $_eol         = "\r\n";

        $this->response->download(urlencode('資産ダウンロード.csv'));
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('assets', '_serialize', '_extract', '_header', '_csvEncoding', '_newline', '_eol', '_bom'));
    }

    /**
     * 廃棄予定一覧データをエクスポートする
     *
     * @return \Cake\Http\Response|void
     */
    public function downloadAbrogatePlans()
    {
        if (!$this->request->is('post')) {
            throw new BadRequestException(__('指定されたページへのアクセスが不正です。'));
        }

        // 廃棄予定一覧を取得
        $assets = $this->ModelAssets->abrogatePlans(true);
        $exp    = $this->_makeDownloadAbrogateDatas($assets);

        // ダウンロード
        $_serialize   = ['assets'];
        $_extract     = $exp['data'];
        $_header      = $exp['header'];
        $_csvEncoding = 'SJIS-win';
        $_newline     = "\r\n";
        $_eol         = "\r\n";

        $this->response->download(urlencode('廃棄予定ダウンロード.csv'));
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('assets', '_serialize', '_extract', '_header', '_csvEncoding', '_newline', '_eol', '_bom'));
    }

    /**
     * 廃棄済一覧データをエクスポートする
     *
     * @return \Cake\Http\Response|void
     */
    public function downloadAbrogates()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 廃棄済一覧を取得
        $assets = $this->ModelAssets->abrogates($data['cond'], true);
        $exp    = $this->_makeDownloadAbrogateDatas($assets);

        // ダウンロード
        $_serialize   = ['assets'];
        $_extract     = $exp['data'];
        $_header      = $exp['header'];
        $_csvEncoding = 'SJIS-win';
        $_newline     = "\r\n";
        $_eol         = "\r\n";

        $this->response->download(urlencode('廃棄済一覧ダウンロード.csv'));
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('assets', '_serialize', '_extract', '_header', '_csvEncoding', '_newline', '_eol', '_bom'));
    }

    /**
     * (プライベート)廃棄予定 or 廃棄済一覧のエクスポート用データ／ヘッダーを作成する
     *
     * @param array $assets 資産データ
     * @return array エクスポート用データ／ヘッダー（[data => エクスポートデータ, header => ヘッダー]）
     */
    private function _makeDownloadAbrogateDatas($assets)
    {
        $exp = [];
        $exp['data'] = [
            function ($row) { return $row['classification']['kname']; },             // 分類名
            function ($row) { return $row['company']['kname']; },                    // メーカー名
            function ($row) { return $row['product']['kname']; },                    // 製品名
            function ($row) { return $row['serial_no']; },                           // シリアル番号
            function ($row) { return $row['asset_no']; },                            // 資産管理番号
            function ($row) { return $row['repairs'][0]['repair_count']; },          // 修理回数
            function ($row) { return $row['abrogate_date']; },                       // 廃棄日
            function ($row) { return $row['asset_abrogate_suser']['kname']; },       // 廃棄者
            function ($row) { return $row['abrogate_reason']; },                     // 廃棄理由
        ];
        $exp['header'] = [
            '分類名', 'メーカー名', '製品名', 'シリアル番号', '資産管理番号', '修理回数', '廃棄日', '廃棄者', '廃棄理由'
        ];

        return $exp;
    }

}
