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
namespace App\Controller;

use \Cake\Event\Event;

/**
 * Home Controller
 *
 *
 * @method Home[] paginate($object = null, array $settings = [])
 */
class HomeController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *
     * - - -
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelInstocks');
        $this->_loadComponent('ModelInstockPlans');
        $this->_loadComponent('ModelPickings');
        $this->_loadComponent('ModelPickingPlans');
        $this->_loadComponent('ModelAssets');
        $this->_loadComponent('ModelStocks');
        $this->_loadComponent('ModelRepairs');
        $this->_loadComponent('ModelCompanies');
        $this->_loadComponent('SysModelSnames');
    }

    /**
     * アクション実行前の処理を行う
     *
     * - - -
     * @param \Cake\Event\Event イベント
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }

    /**
     * ホーム画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function home()
    {
        // 資産数(※PC)
        $assetCount       = $this->ModelAssets->countPc();
        // 在庫数(※PC)
        $stockCount       = $this->ModelStocks->countPc();
        // 修理数(※PC)
        $repairCount      = $this->ModelRepairs->countRepairs();

        // 入庫
        $instockPlans     = $this->ModelInstockPlans->listToday();
        $instockCount     = $this->ModelInstocks->countToday();
        $instockPlanCount         = 0;
        $instockPlansInstockCount = 0;
        foreach ($instockPlans as $instockPlan) {
            $instockPlanCount         = $instockPlanCount + intVal($instockPlan['instock_plan_details'][0]['sum_plan_count']);
            $instockPlansInstockCount = $instockPlansInstockCount + intVal($instockPlan['instocks'][0]['sum_instock_count']);
        }
        $instockPlanRate  = ($instockPlanCount === 0) ? 0 : round(($instockPlansInstockCount/$instockPlanCount),2) * 100;

        // 出庫
        $pickingRequests  = $this->ModelPickingPlans->requestToday();
        $pickingPlans     = $this->ModelPickingPlans->listToday();
        $pickingCount     = $this->ModelPickings->countToday();
        $pickingRequestCount      = count($pickingRequests);
        $pickingPlanCount         = 0;
        $pickingPlansPickingCount = 0;
        foreach ($pickingPlans as $pickingPlan) {
            $pickingPlanCount         = $pickingPlanCount + intVal($pickingPlan['picking_plan_details'][0]['sum_plan_count']);
            $pickingPlansPickingCount = $pickingPlansPickingCount + intVal($pickingPlan['pickings'][0]['sum_picking_count']);
        }

        $pickingPlanRate  = ($pickingPlanCount === 0) ? 0 : round(($pickingPlansPickingCount/$pickingPlanCount),2) * 100;

        $this->set(compact(
            'assetCount', 'stockCount', 'repairCount', 'instockPlans', 'instockCount', 'instockPlanCount', 'instockPlansInstockCount', 'instockPlanRate',
            'pickingPlans', 'pickingCount', 'pickingRequestCount', 'pickingPlanCount', 'pickingPlansPickingCount', 'pickingPlanRate'
        ));

        $this->render();
    }

    /**
     * ドメインを変更する
     *
     * @return \Cake\Http\Response|void
     */
    public function changeDomain()
    {
        if ($this->request->is('post'))
        {
            $data = $this->request->getData();
            if ($data && array_key_exists('to_domain_id', $data)) {
                $this->AppUser->changeCurrent($data['to_domain_id']);
                $this->AppSession->changeAppUser($this->AppUser);
            }
        }

        $this->redirect(['action' => 'home']);
    }

    /**
     * 検索画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function search()
    {
        $data = $this->validateParameter('criteria', ['post']);
        $assets = [];
        if ($data) {
            $assets = $this->ModelAssets->searchCriteria($data['criteria']);
        }

        // 表示用に編集する
        $list = [];
        foreach($assets as $asset) {
            $row = [];
            $row['id']                  = $asset['id'];
            $row['kname']               = $asset['kname'];
            $row['serial_no']           = $asset['serial_no'];
            $row['asset_no']            = $asset['asset_no'];
            $row['asset_sts_name']      = $asset['asset_sts_name']['name'];
            $row['asset_sub_sts_name']  = $asset['asset_sub_sts_name']['name'];
            $row['category_name']       = $asset['classification']['category']['kname'];
            $row['classification_name'] = $asset['classification']['kname'];
            $row['maker_name']          = $asset['company']['kname'];
            $row['product_name']        = $asset['product']['kname'];
            $row['product_model_name']  = $asset['product_model']['kname'];
            $row['user_name']           = ($asset['user']) ? $asset['user']['sname'] . ' ' . $asset['user']['fname'] : '';
            $row['stock_count']         = $asset['stock']['stock_count'];
            $list[] = $row;
        }

        $this->set('criteria', $data['criteria']);
        $this->set('assets', $list);

        // 資産詳細表示用
        $assetSts    = $this->SysModelSnames->byKey('ASSET_STS');
        $assetSubSts = $this->SysModelSnames->byKey('ASSET_SUB_STS');
        $rentalSts   = $this->SysModelSnames->byKey('RENTAL_STS');
        $this->set(compact('makers', 'assetSts', 'assetSubSts', 'rentalSts'));

        $this->render();
    }
}
