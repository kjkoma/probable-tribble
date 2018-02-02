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
 * 交換（Exchanges）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelExchangesComponent extends AppModelComponent
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
        $config['modelName'] = 'Exchanges';
        parent::initialize($config);
    }

    /**
     * 交換情報を検索する
     *  
     * - - -
     * @param array $cond 検索条件
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 交換一覧（ResultSet or Array）
     */
    public function search($cond, $toArray = false)
    {
        $query = $this->modelTable->find('valid')
            ->contain(['ExchangesInstockAssets' => function($q) {
                return $q->select([
                    'id', 'classification_id', 'serial_no', 'asset_no',
                    'maker_id', 'product_id', 'product_model_id', 'kname', 'asset_sts', 'asset_sub_sts']);
            }])
            ->contain(['ExchangesInstockAssets.Products' => function($q) {
                return $q->select([
                    'id', 'kname']);
            }])
            ->contain(['ExchangesPickingAssets' => function($q) {
                return $q->select([
                    'id', 'classification_id', 'serial_no', 'asset_no',
                    'maker_id', 'product_id', 'product_model_id', 'kname', 'asset_sts', 'asset_sub_sts']);
            }])
            ->contain(['ExchangesPickingAssets.Products' => function($q) {
                return $q->select([
                    'id', 'kname']);
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
                'PickingPlans.req_date'             => 'DESC',
                'ExchangesInstockAssets.product_id' => 'ASC',
                'ExchangesPickingAssets.product_id' => 'ASC'
            ]);

        if (array_key_exists('req_date_from', $cond) && $cond['req_date_from'] !== '') {
            $query->where(['PickingPlans.req_date >=' => $cond['req_date_from']]);
        }
        if (array_key_exists('req_date_to', $cond) && $cond['req_date_to'] !== '') {
            $query->where(['PickingPlans.req_date <=' => $cond['req_date_to']]);
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
        if (array_key_exists('already_instock', $cond) && $cond['already_instock'] !== '0') {
            if ($cond['already_instock'] == '1') {
                $query->where(['Exchanges.instock_id IS NOT' => null]);
            } else {
                $query->where(['Exchanges.instock_id IS' => null]);
            }
        }
        if (array_key_exists('product_id', $cond) && $cond['product_id'] !== '') {
            $query->where(['ExchangesInstockAssets.product_id' => $cond['product_id']]);
        }
        if (array_key_exists('serial_no', $cond) && $cond['serial_no'] !== '') {
            $query->where(['ExchangesInstockAssets.serial_no' => $cond['serial_no']]);
        }
        if (array_key_exists('asset_no', $cond) && $cond['asset_no'] !== '') {
            $query->where(['ExchangesInstockAssets.asset_no' => $cond['asset_no']]);
        }
        if (array_key_exists('picking_product_id', $cond) && $cond['picking_product_id'] !== '') {
            $query->where(['ExchangesPickingAssets.product_id' => $cond['picking_product_id']]);
        }
        if (array_key_exists('picking_serial_no', $cond) && $cond['picking_serial_no'] !== '') {
            $query->where(['ExchangesPickingAssets.serial_no' => $cond['picking_serial_no']]);
        }
        if (array_key_exists('picking_asset_no', $cond) && $cond['picking_asset_no'] !== '') {
            $query->where(['ExchangesPickingAssets.asset_no' => $cond['picking_asset_no']]);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 出庫予定IDより該当する交換データを取得する
     * (入庫情報を含む)
     *  
     * - - -
     * @param integer pickingPlanId 出庫予定ID
     * @return \App\Model\Entity\Exchange 交換データ
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
     * 入庫予定詳細IDより該当する交換データを取得する
     *  
     * - - -
     * @param integer instockPlanDetailId 入庫予定詳細ID
     * @return \App\Model\Entity\Exchange 交換データ
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
     * 出庫予定詳細IDより該当する交換データを取得する
     *  
     * - - -
     * @param integer pickingPlanDetailId 出庫予定詳細ID
     * @return \App\Model\Entity\Exchange 交換データ
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
     * 交換情報を新規に作成する（出庫予定登録時）
     *  
     * - - -
     * @param array $exchange 交換情報
     * @param array $asset 資産情報
     * @param array $pickingPlanId 出庫予定情報ID
     * @param array $pickingPlanDetailId 出庫予定情報詳細ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($exchange, $asset, $pickingPlanId, $pickingPlanDetailId)
    {
        // 入庫予定を登録
        $instockPlan = $this->ModelInstockPlans->addByExchange($exchange['instock_plan_date'], $asset, $exchange['exchange_reason']);
        if (!$instockPlan['result']) {
            return $instockPlan;
        }

        // 入庫予定詳細を登録
        $instockPlanDetail = $this->ModelInstockPlanDetails->addByAsset($instockPlan['data']['id'], $asset);
        if (!$instockPlanDetail['result']) {
            return $instockPlanDetail;
        }

        // 交換情報を登録
        $new = [];
        $new['domain_id']              = $this->current();
        $new['picking_plan_id']        = $pickingPlanId;
        $new['picking_plan_detail_id'] = $pickingPlanDetailId;
        $new['instock_plan_id']        = $instockPlan['data']['id'];
        $new['instock_plan_detail_id'] = $instockPlanDetail['data']['id'];
        $new['instock_asset_id']       = $instockPlanDetail['data']['asset_id'];
        $new['exchange_reason']        = $exchange['exchange_reason'];

        return parent::add($new);
    }

    /**
     * 交換情報を保存する（入庫時）
     *  
     * - - -
     * @param array $detailId  入庫予定詳細ID
     * @param array $instockId 入庫ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateInstock($detailId, $instockId)
    {
        // 交換情報を取得
        $exchange = $this->byInstockPlanDetailId($detailId);
        if (!$exchange || count($exchange) == 0) {
            return $this->_invalid('交換情報が存在しないため、更新できませんでした。');
        }

        $exchange['instock_id'] = $instockId;

        // 交換情報を更新
        return parent::save($exchange->toArray());
    }

    /**
     * 交換情報を保存する（出庫予定編集時）
     *  
     * - - -
     * @param array $exchange 交換情報
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function saveByPicking($exchange, $asset)
    {
        // 交換情報を取得
        $current = parent::get($exchange['id']);
        if (!$current || count($current) == 0) {
            return $this->_invalid('交換情報が存在しないため、更新できませんでした。');
        }
        $current['instock_asset_id'] = $asset['id'];
        $current['exchange_reason']  = $exchange['exchange_reason'];

        // 入庫予定を更新
        $instockPlan = $this->ModelInstockPlans->saveByPicking(
            $current['instock_plan_id'], $exchange['instock_plan_date'],
            $current['exchange_reason'], $asset, '交換'
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
     * 交換情報を保存する（出庫時）
     *  
     * - - -
     * @param array $planDetail 出庫予定詳細
     * @param array $detail 出庫詳細
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updatePicking($planDetail, $detail)
    {
        // 交換情報を取得
        $exchange = $this->byPickingPlanDetailId($planDetail['id']);
        if (!$exchange || count($exchange) == 0) {
            return $this->_invalid('交換情報が存在しないため、更新できませんでした。');
        }

        $exchange['picking_id']       = $detail['picking_id'];
        $exchange['picking_asset_id'] = $detail['asset_id'];

        // 交換情報を更新
        return parent::save($exchange->toArray());
    }
}
