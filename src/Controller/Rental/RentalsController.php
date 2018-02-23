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
namespace App\Controller\Rental;

use App\Controller\AppController;

/**
 * Rentals Controller
 *
 */
class RentalsController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelRentals');
        $this->_loadComponent('ModelCompanies');
        $this->_loadComponent('SysModelSnames');
    }

    /**
     * 貸出画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function rental()
    {
        $this->render();
    }

    /**
     * 返却画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function back()
    {
        $this->render();
    }

    /**
     * 検索画面を検索する
     *
     * @return \Cake\Http\Response|void
     */
    public function search()
    {
        $makers      = $this->ModelCompanies->makers();
        $assetSts    = $this->SysModelSnames->byKey('ASSET_STS');
        $assetSubSts = $this->SysModelSnames->byKey('ASSET_SUB_STS');
        $rentalSts   = $this->SysModelSnames->byKey('RENTAL_STS');

        $this->set(compact('makers', 'assetSts', 'assetSubSts', 'rentalSts'));
        $this->render();
    }

    /**
     * 貸出一覧データをエクスポートする
     *
     * @return \Cake\Http\Response|void
     */
    public function download()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 貸出一覧を取得
        $rentals = $this->ModelRentals->search($data['cond'], false);

        // ダウンロード
        $_serialize = ['rentals'];
        $_extract   = [
            function($row) { return $row['rental_sts_name']['name']; },
            function($row) { return $row['req_date']; },
            function($row) { return $row['rental_req_user']['sname'] . ' ' . $row['rental_req_user']['fname']; },
            function($row) { return $row['plan_date']; },
            function($row) { return $row['rental_user']['sname'] . ' ' . $row['rental_user']['fname']; },
            function($row) { return $row['rental_admin_user']['sname'] . ' ' . $row['rental_admin_user']['fname']; },
            function($row) { return $row['rental_date']; },
            function($row) { return $row['rental_suser']['kname']; },
            function($row) { return $row['back_plan_date']; },
            function($row) { return $row['back_date']; },
            function($row) { return $row['rental_back_user']['sname'] . ' ' . $row['rental_back_user']['fname']; },
            function($row) { return $row['rental_back_suser']['kname']; },
            function($row) { return $row['asset']['asset_no']; },
            function($row) { return $row['asset']['serial_no']; },
            function($row) { return $row['asset']['classification']['kname']; },
            function($row) { return $row['asset']['company']['kname']; },
            function($row) { return $row['asset']['product']['kname']; },
            function($row) { return $row['asset']['product_model']['kname']; },
            function($row) { return $row['asset']['kname']; },
            function($row) { return $row['rental_remarks']; },
            function($row) { return $row['remarks']; },
        ];
        $_header    = [
            '貸出状況', '依頼日', '依頼者', '希望日', '利用者', '管理者', '貸出日', '貸出者', '返却予定日',
            '返却日', '返却者', '受領者', '資産管理番号', 'シリアル番号', '分類', 'メーカー',
            '製品名', 'モデル／型', '資産名', '貸出メモ', '備考'
        ];
        $_csvEncoding = 'SJIS-win';
        $_newline     = "\r\n";
        $_eol         = "\r\n";

        $this->response->download(urlencode('貸出一覧ダウンロード.csv'));
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('rentals', '_serialize', '_extract', '_header', '_csvEncoding', '_newline', '_eol', '_bom'));
    }

}
