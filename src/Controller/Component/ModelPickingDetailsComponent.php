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
 * 出庫詳細（PickingDetails）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelPickingDetailsComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    // public $components = [''];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'PickingDetails';
        parent::initialize($config);
    }

    /**
     * 出庫詳細を新規に登録する
     *  
     * - - -
     * @param array $picking 出庫データ
     * @param array $asset 資産データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($picking, $asset) {
        $detail = [];
        $detail['domain_id']  = $this->current();
        $detail['picking_id'] = $picking['id'];
        $detail['asset_id']   = $asset['id'];
        $detail['serial_no']  = $asset['serial_no'];
        $detail['asset_no']   = $asset['asset_no'];

        return parent::add($detail);
    }

    /**
     * 出庫詳細を検索する
     *  
     * - - -
     * @param array $cond 検索条件
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 検索結果（ResultSet or Array）
     */
    public function search($cond, $toArray = false)
    {
        $hasCondition = false;

        $query = $this->modelTable->find('validAll')
            ->select([
                'id', 'picking_id', 'asset_id'
            ])
            ->contain([
                'Assets' => function($q) { return $q->select([
                        'id', 'asset_type', 'classification_id', 'serial_no', 'asset_no',
                        'maker_id', 'product_id', 'product_model_id', 'kname', 'asset_sts', 'asset_sub_sts'
                    ]); }
            ])
            ->contain([
                'Assets.Classifications' => function($q) { return $q->select([
                        'id', 'kname', 'category_id'
                    ]); }
            ])
            ->contain([
                'Assets.Companies' => function($q) { return $q->select([
                        'id', 'kname'
                    ]); }
            ])
            ->contain([
                'Assets.Products' => function($q) { return $q->select([
                        'id', 'kname'
                    ]); }
            ])
            ->contain([
                'Assets.ProductModels' => function($q) { return $q->select([
                        'id', 'kname'
                    ]); }
            ])
            ->contain([
                'Assets.AssetStsName' => function($q) { return $q->select([
                        'id', 'nkey', 'nid', 'name'
                    ]); }
            ])
            ->contain([
                'Assets.AssetSubStsName' => function($q) { return $q->select([
                        'id', 'nkey', 'nid', 'name'
                    ]); }
            ])
            ->contain([
                'Pickings' => function($q) { return $q->select([
                        'id', 'picking_kbn', 'picking_plan_id', 'picking_plan_detail_id', 'picking_date',
                        'picking_suser_id', 'confirm_suser_id', 'picking_count', 'delivery_company_id', 'voucher_no', 'remarks'
                    ]); }
            ])
            ->contain([
                'Pickings.PickingKbnName' => function($q) { return $q->select([
                        'id', 'nkey', 'nid', 'name'
                    ]); }
            ])
            ->contain([
                'Pickings.PickingConfirmSusers' => function($q) { return $q->select([
                        'id', 'kname'
                    ]); }
            ])
            ->contain([
                'Pickings.PickingSusers' => function($q) { return $q->select([
                        'id', 'kname'
                    ]); }
            ])
            ->contain([
                'Pickings.PickingPlans' => function($q) { return $q->select([
                        'id', 'name', 'plan_date', 'req_date', 'req_user_id', 'req_emp_no', 'use_user_id', 'use_emp_no',
                        'dlv_user_id', 'dlv_emp_no', 'dlv_name', 'dlv_tel', 'dlv_zip', 'dlv_address', 'arv_date',
                        'rcv_date', 'picking_reason', 'work_suser_id'
                    ]); }
            ])
            ->contain([
                'Pickings.PickingPlans.PickingPlanReqUsers' => function($q) { return $q->select([
                        'id', 'fname', 'sname'
                    ]); }
            ])
            ->contain([
                'Pickings.PickingPlans.PickingPlanUseUsers' => function($q) { return $q->select([
                        'id', 'fname', 'sname'
                    ]); }
            ])
            ->contain([
                'Pickings.PickingPlans.PickingPlanDlvUsers' => function($q) { return $q->select([
                        'id', 'fname', 'sname'
                    ]); }
            ])
            ->contain([
                'Pickings.PickingPlans.PickingPlanWorkSusers' => function($q) { return $q->select([
                        'id', 'kname'
                    ]); }
            ])
            ->contain([
                'Pickings.PickingPlanDetails' => function($q) { return $q->select([
                        'id', 'picking_plan_id', 'kitting_pattern_id', 'reuse_kbn', 'apply_no'
                    ]); }
            ])
            ->order([
                'Pickings.picking_date'     => 'DESC',
                'Pickings.voucher_no'       => 'ASC',
                'PickingDetails.asset_no'   => 'ASC', 
                'PickingDetails.serial_no'  => 'ASC', 
                'PickingDetails.asset_id'   => 'ASC' 
            ]);

        if ($this->hasSearchParams('classification_id', $cond)) {
            $query->where(['Assets.classification_id IN' => $cond['classification_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('maker_id', $cond)) {
            $query->where(['Assets.maker_id' => $cond['maker_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('product_id', $cond)) {
            $query->where(['Assets.product_id' => $cond['product_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('product_model_id', $cond)) {
            $query->where(['Assets.product_model_id' => $cond['product_model_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('serial_no', $cond)) {
            $query->where(['Assets.serial_no' => $cond['serial_no']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('asset_no', $cond)) {
            $query->where(['Assets.asset_no' => $cond['asset_no']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('picking_date_from', $cond)) {
            $query->where(['Pickings.picking_date >=' => $cond['picking_date_from']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('picking_date_to', $cond)) {
            $query->where(['Pickings.picking_date <=' => $cond['picking_date_to']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('picking_suser_id', $cond)) {
            $query->where(['Pickings.picking_suser_id' => $cond['picking_suser_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('confirm_suser_id', $cond)) {
            $query->where(['Pickings.confirm_suser_id' => $cond['confirm_suser_id']]);
            $hasCondition = true;
        }
        if (array_key_exists('not_confirmation', $cond) && $cond['not_confirmation'] == '1') {
            $query->where(['Pickings.confirm_suser_id IS' => null]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('voucher_no', $cond)) {
            $query->where(['Pickings.voucher_no' => $cond['voucher_no']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('remarks', $cond)) {
            $query->where(['Pickings.remarks like' => '%' . $cond['remarks'] . '%']);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('req_user_id', $cond)) {
            $query->where(['PickingPlanReqUsers.req_user_id' => $cond['req_user_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('use_user_id', $cond)) {
            $query->where(['PickingPlanUseUsers.use_user_id' => $cond['use_user_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('dlv_user_id', $cond)) {
            $query->where(['PickingPlanDlvUsers.dlv_user_id' => $cond['dlv_user_id']]);
            $hasCondition = true;
        }


        if (!$hasCondition) {
            $query = $this->modelTable->find('valid')->where(['1 = 0']); // 検索しない
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

}
