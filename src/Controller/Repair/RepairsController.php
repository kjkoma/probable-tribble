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
namespace App\Controller\Repair;

use App\Controller\AppController;

/**
 * Repairs Controller
 *
 */
class RepairsController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelRepairs');
        $this->_loadComponent('ModelCompanies');
        $this->_loadComponent('SysModelSnames');
    }

    /**
     * 修理一覧を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function list()
    {
        $makers      = $this->ModelCompanies->makers();
        $repairSts   = $this->SysModelSnames->byKey('REPAIR_STS');
        $troubleKbn  = $this->SysModelSnames->byKey('TROUBLE_KBN');
        $sendbackKbn = $this->SysModelSnames->byKey('SENDBACK_KBN');
        $datapickKbn = $this->SysModelSnames->byKey('DATAPICK_KBN');

        $this->set(compact('makers', 'repairSts', 'troubleKbn', 'sendbackKbn', 'datapickKbn'));
        $this->render();
    }

    /**
     * 修理一覧データをエクスポートする
     *
     * @return \Cake\Http\Response|void
     */
    public function downloadList()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 修理一覧を取得
        $repairs = $this->ModelRepairs->search($data['cond'], true);

        // ダウンロード
        $_serialize     = ['repairs'];
        $_extract   = [
            function($row) { return $row['repair_st']['name']; },
            function($row) { return $row['picking_plan']['req_date']; },
            function($row) { return $row['picking_plan']['picking_plan_req_user']['sname'] . ' ' . $row['picking_plan']['picking_plan_req_user']['fname']; },
            function($row) { return $row['repair_asset']['classification']['category_id']; },
            function($row) { return $row['repair_asset']['classification']['category']['kname']; },
            function($row) { return $row['repair_asset']['classification_id']; },
            function($row) { return $row['repair_asset']['classification']['kname']; },
            function($row) { return $row['repair_asset']['maker_id']; },
            function($row) { return $row['repair_asset']['company']['kname']; },
            function($row) { return $row['repair_asset']['product_id']; },
            function($row) { return $row['repair_asset']['product']['kname']; },
            function($row) { return $row['repair_asset']['product_model_id']; },
            function($row) { return $row['repair_asset']['product_model']['kname']; },
            function($row) { return $row['repair_asset']['asset_no']; },
            function($row) { return $row['repair_asset']['serial_no']; },
            function($row) { return $row['repairs_picking_asset']['asset_no']; },
            function($row) { return $row['repairs_picking_asset']['serial_no']; },
            function($row) { return $row['repairs_sendback_kbn']['name']; },
            function($row) { return $row['repairs_datapick_kbn']['name']; },
            function($row) { return $row['repairs_trouble_kbn']['name']; },
            function($row) { return $row['trouble_reason']; },
        ];
        $_header    = [
            '修理状況', '依頼日', '依頼者', 'カテゴリID', 'カテゴリ名', '分類ID', '分類名', 'メーカーID', 'メーカー名',
            '製品ID', '製品名', 'モデル(型)ID', 'モデル(型)名', '資産管理番号(入庫)', 'シリアル番号(入庫)', '資産管理番号(出庫)', 'シリアル番号(出庫)',
            'センドバック', 'データ抽出', '故障区分', '故障原因'
        ];
        $_csvEncoding = 'SJIS-win';
        $_newline     = "\r\n";
        $_eol         = "\r\n";

        $this->response->download(urlencode('修理一覧ダウンロード.csv'));
        $this->viewBuilder()->className('CsvView.Csv');
        $this->set(compact('repairs', '_serialize', '_extract', '_header', '_csvEncoding', '_newline', '_eol', '_bom'));
    }

}
