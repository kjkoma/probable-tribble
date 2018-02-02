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
namespace App\Controller\Component;

use Cake\Core\Configure;

/**
 * 修理（Repairs）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelRepairsComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['ModelInstockPlans', 'ModelInstockPlanDetails'];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'Repairs';
        parent::initialize($config);
    }

    /**
     * 現在の修理件数を取得する
     *  
     * - - -
     * 
     * @return integer 修理件数
     */
    public function countRepairs()
    {
        $query = $this->modelTable->find('validAll')
            ->andWhere(['Repairs.repair_sts IN' => [
                Configure::read('WNote.DB.Repair.RepairSts.instock_plan'),
                Configure::read('WNote.DB.Repair.RepairSts.instock'),
                Configure::read('WNote.DB.Repair.RepairSts.repair'),
                Configure::read('WNote.DB.Repair.RepairSts.picking')
        ]]);

        return $query->count();
    }

    /**
     * 修理情報を検索する
     *  
     * - - -
     * @param array $cond 検索条件
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 修理一覧（ResultSet or Array）
     */
    public function search($cond, $toArray = false)
    {
        $query = $this->modelTable->find('valid')
            ->contain(['RepairRepairKbn' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['RepairSts' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['RepairsTroubleKbn' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['RepairsSendbackKbn' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['RepairsDatapickKbn' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['RepairAssets' => function($q) {
                return $q->select([
                    'id', 'classification_id', 'serial_no', 'asset_no',
                    'maker_id', 'product_id', 'product_model_id', 'kname', 'asset_sts', 'asset_sub_sts']);
            }])
            ->contain(['RepairAssets.AssetStsName' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['RepairAssets.AssetSubStsName' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['RepairAssets.Classifications.Categories' => function($q) {
                return $q->select([
                    'id', 'kname']);
            }])
            ->contain(['RepairAssets.Classifications' => function($q) {
                return $q->select([
                    'id', 'category_id', 'kname']);
            }])
            ->contain(['RepairAssets.Companies' => function($q) {
                return $q->select([
                    'id', 'kname']);
            }])
            ->contain(['RepairAssets.Products' => function($q) {
                return $q->select([
                    'id', 'kname']);
            }])
            ->contain(['RepairAssets.ProductModels' => function($q) {
                return $q->select([
                    'id', 'kname']);
            }])
            ->contain(['RepairsPickingAssets' => function($q) {
                return $q->select(['id', 'serial_no', 'asset_no']);
            }])
            ->contain(['PickingPlans' => function($q) {
                return $q->select([
                    'id', 'req_date', 'req_user_id']);
            }])
            ->contain(['PickingPlans.PickingPlanReqUsers' => function($q) {
                return $q->select([
                    'id', 'fname', 'sname']);
            }])
            ->contain(['Instocks' => function($q) {
                return $q->select([
                    'id', 'instock_date']);
            }])
            ->contain(['Pickings' => function($q) {
                return $q->select([
                    'id', 'picking_date']);
            }])
            ->order([
                'PickingPlans.req_date'   => 'DESC',
                'RepairAssets.product_id' => 'ASC'
            ]);

        if (array_key_exists('repair_sts', $cond) && $cond['repair_sts'] !== '') {
            $query->where(['repair_sts' => $cond['repair_sts']]);
        }
        if (array_key_exists('req_date_from', $cond) && $cond['req_date_from'] !== '') {
            $query->where(['PickingPlans.req_date >=' => $cond['req_date_from']]);
        }
        if (array_key_exists('req_date_to', $cond) && $cond['req_date_to'] !== '') {
            $query->where(['PickingPlans.req_date <=' => $cond['req_date_to']]);
        }
        if (array_key_exists('trouble_kbn', $cond) && $cond['trouble_kbn'] !== '') {
            $query->where(['trouble_kbn' => $cond['trouble_kbn']]);
        }
        if (array_key_exists('sendback_kbn', $cond) && $cond['sendback_kbn'] !== '') {
            $query->where(['sendback_kbn' => $cond['sendback_kbn']]);
        }
        if (array_key_exists('datapick_kbn', $cond) && $cond['datapick_kbn'] !== '') {
            $query->where(['datapick_kbn' => $cond['datapick_kbn']]);
        }
        if (array_key_exists('req_user_id', $cond) && $cond['req_user_id'] !== '') {
            $query->where(['PickingPlans.req_user_id' => $cond['req_user_id']]);
        }
        if (array_key_exists('instock_date_from', $cond) && $cond['instock_date_from'] !== '') {
            $query->where(['Instocks.instock_date >=' => $cond['instock_date_from']]);
        }
        if (array_key_exists('instock_date_to', $cond) && $cond['instock_date_to'] !== '') {
            $query->where(['Instocks.instock_date <=' => $cond['instock_date_to']]);
        }
        if (array_key_exists('classification_id', $cond) && $cond['classification_id'] !== '') {
            $query->where(['RepairAssets.classification_id' => $cond['classification_id']]);
        }
        if (array_key_exists('maker_id', $cond) && $cond['maker_id'] !== '') {
            $query->where(['RepairAssets.maker_id' => $cond['maker_id']]);
        }
        if (array_key_exists('product_id', $cond) && $cond['product_id'] !== '') {
            $query->where(['RepairAssets.product_id' => $cond['product_id']]);
        }
        if (array_key_exists('product_model_id', $cond) && $cond['product_model_id'] !== '') {
            $query->where(['RepairAssets.product_model_id' => $cond['product_model_id']]);
        }
        if (array_key_exists('serial_no', $cond) && $cond['serial_no'] !== '') {
            $query->where(['RepairAssets.serial_no' => $cond['serial_no']]);
        }
        if (array_key_exists('asset_no', $cond) && $cond['asset_no'] !== '') {
            $query->where(['RepairAssets.asset_no' => $cond['asset_no']]);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 資産IDより該当する修理データを取得する
     *  
     * - - -
     * @param string assetId 資産ID
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 修理一覧（ResultSet or Array）
     */
    public function listByAssetId($assetId, $toArray = false)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'repair_asset_id' => $assetId
            ])
            ->contain(['RepairRepairKbn' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['RepairSts' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['RepairsTroubleKbn' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 出庫予定IDより該当する修理データを取得する
     * (入庫情報を含む)
     *  
     * - - -
     * @param integer pickingPlanId 出庫予定ID
     * @return \App\Model\Entity\Repair 修理データ
     */
    public function byPickingPlanId($pickingPlanId)
    {
        return $this->modelTable->find('valid')
            ->where([
                'picking_plan_id' => $pickingPlanId
            ])
            ->contain(['InstockPlans' => function($q) {
                return $q->select(['id', 'plan_date', 'plan_sts']);
            }])
            ->contain(['InstockPlanDetails' => function($q) {
                return $q->select(['id', 'asset_id']);
            }])
            ->contain(['InstockPlanDetails.Assets' => function($q) {
                return $q->select(['id', 'asset_no', 'serial_no']);
            }])
            ->first();
    }

    /**
     * 入庫予定詳細IDより該当する修理データを取得する
     *  
     * - - -
     * @param integer instockPlanDetailId 入庫予定詳細ID
     * @return \App\Model\Entity\Repair 修理データ
     */
    public function byInstockPlanDetailId($instockPlanDetailId)
    {
        return $this->modelTable->find('valid')
            ->where([
                'instock_plan_detail_id' => $instockPlanDetailId
            ])
            ->first();
    }

    /**
     * 出庫予定詳細IDより該当する修理データを取得する
     *  
     * - - -
     * @param integer pickingPlanDetailId 出庫予定詳細ID
     * @return \App\Model\Entity\Repair 修理データ
     */
    public function byPickingPlanDetailId($pickingPlanDetailId)
    {
        return $this->modelTable->find('valid')
            ->where([
                'picking_plan_detail_id' => $pickingPlanDetailId
            ])
            ->first();
    }

    /**
     * 修理情報を新規に作成する（出庫予定登録時）
     *  
     * - - -
     * @param array $repair 修理情報
     * @param array $asset 資産情報
     * @param array $pickingPlanId 出庫予定情報ID
     * @return \App\Model\Entity\Exchange 交換データ
     */
    public function addNew($repair, $asset, $pickingPlanId)
    {
        // 入庫予定を登録
        $instockPlan = $this->ModelInstockPlans->addByRepair($repair['instock_plan_date'], $asset, $repair['trouble_reason']);
        if (!$instockPlan['result']) {
            return $instockPlan;
        }

        // 入庫予定詳細を登録
        $instockPlanDetail = $this->ModelInstockPlanDetails->addByAsset($instockPlan['data']['id'], $asset);
        if (!$instockPlanDetail['result']) {
            return $instockPlanDetail;
        }

        // 修理情報を登録
        $new = [];
        $new['domain_id']              = $this->current();
        $new['repair_kbn']             = Configure::read('WNote.DB.Repair.RepairKbn.useage');
        $new['repair_sts']             = Configure::read('WNote.DB.Repair.RepairSts.instock_plan');
        $new['start_date']             = $this->today();
        $new['repair_asset_id']        = $instockPlanDetail['data']['asset_id'];
        $new['instock_plan_id']        = $instockPlan['data']['id'];
        $new['instock_plan_detail_id'] = $instockPlanDetail['data']['id'];
        $new['picking_plan_id']        = $pickingPlanId;
        $new['trouble_kbn']            = $repair['trouble_kbn'];
        $new['trouble_reason']         = $repair['trouble_reason'];
        $new['sendback_kbn']           = $repair['sendback_kbn'];
        $new['datapick_kbn']           = $repair['datapick_kbn'];
        return parent::add($new);
    }

    /**
     * 修理情報を保存する（入庫時）
     *  
     * - - -
     * @param array $detailId 入庫予定詳細ID
     * @param array $instockId 入庫ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateInstock($detailId, $instockId)
    {
        // 修理情報を取得
        $repair = $this->byInstockPlanDetailId($detailId);
        if (!$repair || count($repair) == 0) {
            return $this->_invalid('修理情報が存在しないため、更新できませんでした。');
        }

        $repair['repair_sts'] = Configure::read('WNote.DB.Repair.RepairSts.instock');
        $repair['instock_id'] = $instockId;

        // 修理情報を更新
        return parent::save($repair->toArray());
    }

    /**
     * 修理情報を保存する（出庫予定編集時）
     *  
     * - - -
     * @param array $repair 修理情報
     * @param array $asset 資産情報
     * @return \App\Model\Entity\Exchange 交換データ
     */
    public function saveByPicking($repair, $asset)
    {
        // 修理情報を取得
        $current = $this->modelTable->get($repair['id']);
        if (!$current || count($current) == 0) {
            return $this->_invalid('修理情報が存在しないため、更新できませんでした。');
        }
        $current['repair_asset_id']  = $asset['id'];
        $current['trouble_kbn']      = $repair['trouble_kbn'];
        $current['trouble_reason']   = $repair['trouble_reason'];
        $current['sendback_kbn']     = $repair['sendback_kbn'];
        $current['datapick_kbn']     = $repair['datapick_kbn'];

        // 入庫予定を更新
        $instockPlan = $this->ModelInstockPlans->saveByPicking(
            $current['instock_plan_id'], $repair['instock_plan_date'],
            $current['trouble_reason'], $asset, '修理'
        );
        if (!$instockPlan['result']) {
            return $instockPlan;
        }

        // 入庫予定詳細を更新
        $instockPlanDetail = $this->ModelInstockPlanDetails->saveByAsset($current['instock_plan_detail_id'], $asset);
        if (!$instockPlanDetail['result']) {
            return $instockPlanDetail;
        }

        // 交換情報を更新
        return parent::save($current->toArray());
    }

    /**
     * 修理情報を保存する（出庫時）
     *  
     * - - -
     * @param array $planDetail 出庫予定詳細
     * @param array $detail 出庫詳細
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updatePicking($planDetail, $detail)
    {
        // 修理情報を取得
        $repair = $this->byPickingPlanDetailId($planDetail['id']);
        if (!$repair || count($repair) == 0) {
            return $this->_invalid('修理情報が存在しないため、更新できませんでした。');
        }

        $repair['repair_sts']       = Configure::read('WNote.DB.Repair.RepairSts.picking');
        $repair['end_date']         = $this->today();
        $repair['picking_id']       = $detail['picking_id'];
        $repair['picking_asset_id'] = $detail['asset_id'];

        // 修理情報を更新
        return parent::save($repair->toArray());
    }

}
