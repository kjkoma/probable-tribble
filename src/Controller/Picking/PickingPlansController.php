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
 * Picking Plans Controller
 *
 */
class PickingPlansController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelPickingPlanDetails');
        $this->_loadComponent('ModelCategories');
        $this->_loadComponent('SysModelSnames');
    }

    /**
     * 出庫依頼登録画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function entry()
    {
        $categories  = $this->ModelCategories->valid();
        $pickingKbn  = $this->SysModelSnames->byKey('PICKING_KBN');
        $timeKbn     = $this->SysModelSnames->byKey('TIME_KBN');
        $reuseKbn    = $this->SysModelSnames->byKey('REUSE_KBN');
        $troubleKbn  = $this->SysModelSnames->byKey('TROUBLE_KBN');
        $sendbackKbn = $this->SysModelSnames->byKey('SENDBACK_KBN');
        $datapickKbn = $this->SysModelSnames->byKey('DATAPICK_KBN');

        $this->set(compact('categories', 'pickingKbn', 'timeKbn', 'reuseKbn', 'troubleKbn', 'sendbackKbn', 'datapickKbn'));
        $this->render();
    }

    /**
     * 出庫予定一覧画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function list()
    {
        $pickingSts = $this->SysModelSnames->byKey('PICKING_STS');
        $assetSts    = $this->SysModelSnames->byKey('ASSET_STS');
        $assetSubSts = $this->SysModelSnames->byKey('ASSET_SUB_STS');

        $this->set(compact('pickingSts', 'assetSts', 'assetSubSts'));
        $this->render();
    }

    /**
     * 出庫一覧を検索する
     *
     * @return \Cake\Http\Response|void
     */
    public function seach()
    {
        $this->render();
    }

    /**
     * 出庫予定一覧データをエクスポートする
     *
     * @return \Cake\Http\Response|void
     */
    public function downloadPlan()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 出庫予定一覧を取得
        $plans = $this->ModelPickingPlanDetails->plans($data['cond'], true);

        // ダウンロード
        $_serialize     = ['plans'];
        $_extract   = [
            function($row) { return $row['picking_plan']['picking_plan_picking_kbn']['name']; },
            function($row) { return $row['picking_plan']['picking_plan_st']['name']; },
            function($row) { return $row['picking_plan']['plan_date']; },
            function($row) { return $row['picking_plan']['req_date']; },
            function($row) { return $row['picking_plan']['picking_plan_req_organization']['kname']; },
            function($row) { return $row['picking_plan']['picking_plan_req_user']['sname'] . ' ' . $row['picking_plan']['picking_plan_req_user']['fname']; },
            function($row) { return $row['picking_plan']['req_emp_no']; },
            function($row) { return $row['picking_plan']['picking_plan_use_organization']['kname']; },
            function($row) { return $row['picking_plan']['picking_plan_use_user']['sname'] . ' ' . $row['picking_plan']['picking_plan_use_user']['fname']; },
            function($row) { return $row['picking_plan']['use_emp_no']; },
            function($row) { return $row['picking_plan']['picking_plan_dlv_organization']['kname']; },
            function($row) { return $row['picking_plan']['picking_plan_dlv_user']['sname'] . ' ' . $row['picking_plan']['picking_plan_dlv_user']['fname']; },
            function($row) { return $row['picking_plan']['dlv_emp_no']; },
            function($row) { return $row['picking_plan']['dlv_name']; },
            function($row) { return $row['picking_plan']['dlv_tel']; },
            function($row) { return $row['picking_plan']['dlv_zip']; },
            function($row) { return $row['picking_plan']['dlv_address']; },
            function($row) { return $row['picking_plan']['arv_date']; },
            function($row) { return $row['picking_plan']['picking_plan_time_kbn']['name']; },
            function($row) { return $row['picking_plan']['arv_remarks']; },
            function($row) { return $row['picking_plan']['rcv_date']; },
            function($row) { return $row['picking_plan']['picking_plan_rcv_suser']['kname']; },
            function($row) { return $row['picking_plan']['picking_reason']; },
            function($row) { return $row['apply_no']; },
            function($row) { return $row['category']['kname']; },
            function($row) { return $row['picking_plan_detail_reuse_kbn']['name']; },
            function($row) { return $row['kitting_pattern']['kname']; },
            function($row) { return $row['picking_plan']['picking_plan_work_suser']['kname']; },
            function($row) { return $row['classification']['kname']; },
            function($row) { return $row['product']['kname']; },
            function($row) { return $row['product_model']['kname']; },
            function($row) { return $row['asset']['serial_no']; },
            function($row) { return $row['asset']['asset_no']; },
            function($row) { return $row['picking_plan']['cancel_reason']; },
        ];
        $_header    = [
            '出庫区分', '出庫状況', '予定日', '依頼日', '依頼者(組織)', '依頼者', '依頼者(社員番号)', '使用者(組織)', '使用者', '使用者(社員番号)',
            '出庫先(組織)', '出庫先', '出庫先(社員番号)', '出庫先(宛)', '出庫先(連絡先)', '出庫先(郵便番号)', '出庫先(住所)', '希望日', '希望日時', '希望メモ',
            '受付日', '受付者', '出庫理由', '申請番号', 'カテゴリ','再利用区分', 'キッティングパターン', '作業者', '分類', '製品', 'モデル(型)',
            'シリアル', '資産管理', '取消理由'
        ];
        $_csvEncoding = 'SJIS-win';
        $_newline     = "\r\n";
        $_eol         = "\r\n";

        $this->response->download(urlencode('出庫予定一覧ダウンロード.csv'));
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('plans', '_serialize', '_extract', '_header', '_csvEncoding', '_newline', '_eol', '_bom'));
    }
}
