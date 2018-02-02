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
namespace App\Controller\Picking;

use App\Controller\AppController;

/**
 * Pickings Controller
 *
 */
class PickingsController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelPickingDetails');
        $this->_loadComponent('ModelCompanies');
        $this->_loadComponent('SysModelSnames');
    }

    /**
     * 出庫画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $delivers = $this->ModelCompanies->delivers();
        $makers   = $this->ModelCompanies->makers();
        $assetSts    = $this->SysModelSnames->byKey('ASSET_STS');
        $assetSubSts = $this->SysModelSnames->byKey('ASSET_SUB_STS');

        $this->set(compact('delivers', 'makers', 'assetSts', 'assetSubSts'));
        $this->render();
    }

    /**
     * 出庫検索画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function search()
    {
        $makers = $this->ModelCompanies->makers();

        $this->set(compact('makers'));
        $this->render();
    }

    /**
     * 出庫一覧データをエクスポートする
     *
     * @return \Cake\Http\Response|void
     */
    public function download()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 出庫予定一覧を取得
        $pickings = $this->ModelPickingDetails->search($data['cond'], true);

        // ダウンロード
        $_serialize     = ['pickings'];
        $_extract   = [
            function($row) { return $row['picking']['picking_kbn_name']['name']; },
            function($row) { return $row['picking']['picking_plan']['plan_date']; },
            function($row) { return $row['picking']['picking_date']; },
            function($row) { return $row['picking']['picking_plan']['req_date']; },
            function($row) { return $row['picking']['picking_plan']['picking_plan_req_user']['sname'] . ' ' . $row['picking']['picking_plan']['picking_plan_req_user']['fname']; },
            function($row) { return $row['picking']['picking_plan']['req_emp_no']; },
            function($row) { return $row['picking']['picking_plan']['picking_plan_use_user']['sname'] . ' ' . $row['picking']['picking_plan']['picking_plan_use_user']['fname']; },
            function($row) { return $row['picking']['picking_plan']['use_emp_no']; },
            function($row) { return $row['picking']['picking_plan']['picking_plan_dlv_user']['sname'] . ' ' . $row['picking']['picking_plan']['picking_plan_dlv_user']['fname']; },
            function($row) { return $row['picking']['picking_plan']['dlv_emp_no']; },
            function($row) { return $row['picking']['picking_plan']['dlv_name']; },
            function($row) { return $row['picking']['picking_plan']['dlv_tel']; },
            function($row) { return $row['picking']['picking_plan']['dlv_zip']; },
            function($row) { return $row['picking']['picking_plan']['dlv_address']; },
            function($row) { return $row['picking']['picking_plan']['arv_date']; },
            function($row) { return $row['picking']['picking_plan']['rcv_date']; },
            function($row) { return $row['picking']['picking_reason']; },
            function($row) { return $row['picking']['picking_plan_detail']['apply_no']; },
            function($row) { return $row['asset']['classification']['kname']; },
            function($row) { return $row['asset']['company']['kname']; },
            function($row) { return $row['asset']['product']['kname']; },
            function($row) { return $row['asset']['product_model']['kname']; },
            function($row) { return $row['asset']['kname']; },
            function($row) { return $row['asset']['asset_no']; },
            function($row) { return $row['asset']['serial_no']; },
            function($row) { return $row['asset']['asset_sts_name']['name']; },
            function($row) { return $row['asset']['asset_sub_sts_name']['name']; },
        ];
        $_header    = [
            '出庫区分', '予定日', '出庫日', '依頼日', '依頼者', '依頼者(社員番号)', '使用者', '使用者(社員番号)',
            '出庫先', '出庫先(社員番号)', '出庫先(宛)', '出庫先(連絡先)', '出庫先(郵便番号)', '出庫先(住所)', '希望日',
            '受付日', '出庫理由', '申請番号', '資産分類', 'メーカー', '製品', 'モデル(型)', '資産名', 'シリアル番号',
            '資産管理番号', '資産状況', '資産状況(サブ)'
        ];
        $_csvEncoding = 'SJIS-win';
        $_newline     = "\r\n";
        $_eol         = "\r\n";

        $this->response->download(urlencode('出庫一覧ダウンロード.csv'));
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('pickings', '_serialize', '_extract', '_header', '_csvEncoding', '_newline', '_eol', '_bom'));
    }
}
