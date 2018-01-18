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
use Cake\ORM\Query;

/**
 * 出庫予定詳細（PickingPlanDetails）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelPickingPlanDetailsComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['ModelAssets', 'ModelStocks', 'ModelPickingPlans'];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'PickingPlanDetails';
        parent::initialize($config);
    }

    /**
     * 出庫予定詳細を取得する
     *
     * - - -
     * @param string detailId 出庫予定詳細ID
     * @return \App\Model\Entity\PickingPlanDetail 出庫予定詳細
     */
    public function plan($detailId)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'PickingPlanDetails.id' => $detailId
            ]);

        // 関連を追加
        $query = $this->_addAssociation($query);

        return $query->first();
    }

    /**
     * 出庫予定詳細一覧を取得する
     *  
     * - - -
     * @param array $cond 検索条件
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 出庫予定詳細一覧（ResultSet or Array）
     */
    public function plans($cond, $toArray = false)
    {
        $query = $this->modelTable->find('validAll')
            ->andWhere( ['PickingPlans.plan_sts <>' => Configure::read('WNote.DB.Picking.PickingSts.complete') ])
            ->andWhere( ['PickingPlans.plan_sts <>' => Configure::read('WNote.DB.Picking.PickingSts.cancelfix') ])
            ->order([
                'PickingPlans.plan_date'   => 'ASC',
                'PickingPlans.req_date'    => 'ASC',
                'PickingPlans.arv_date'    => 'ASC',
                'PickingPlans.req_user_id' => 'ASC',
                'apply_no'                 => 'ASC',
            ]);

        // 関連を追加
        $query = $this->_addAssociation($query);

        // 検索条件を追加
        if ($this->hasSearchParams('id', $cond)) {
            $query->andWhere(['PickingPlanDetails.id' => $cond['id']]);
        }
        if ($this->hasSearchParams('plan_date_from', $cond)) {
            $query->andWhere(['PickingPlans.plan_date >=' => $cond['plan_date_from']]);
        }
        if ($this->hasSearchParams('plan_date_to', $cond)) {
            $query->andWhere(['PickingPlans.plan_date <=' => $cond['plan_date_to']]);
        }
        if ($this->hasSearchParams('has_picking_plan', $cond) && $cond['has_picking_plan'] == '2') { // 出庫予定あり
            $query->andWhere(['PickingPlans.plan_date IS NOT' => null]);
            $query->andWhere(['PickingPlans.plan_sts <> ' => Configure::read('WNote.DB.Picking.PickingSts.cancelfix')]);
            $query->andWhere(['PickingPlans.plan_sts <> ' => Configure::read('WNote.DB.Picking.PickingSts.cancel')]);
        }
        if ($this->hasSearchParams('has_picking_plan', $cond) && $cond['has_picking_plan'] == '3') { // 出庫予定なし
            $query->andWhere(['PickingPlans.plan_date IS' => null]);
            $query->andWhere(['PickingPlans.plan_sts <> ' => Configure::read('WNote.DB.Picking.PickingSts.cancelfix')]);
            $query->andWhere(['PickingPlans.plan_sts <> ' => Configure::read('WNote.DB.Picking.PickingSts.cancel')]);
        }
        if ($this->hasSearchParams('plan_sts', $cond)) {
            $query->andWhere(['PickingPlans.plan_sts' => $cond['plan_sts']]);
        }
        if ($this->hasSearchParams('req_date_from', $cond)) {
            $query->andWhere(['PickingPlans.req_date >=' => $cond['req_date_from']]);
        }
        if ($this->hasSearchParams('req_date_to', $cond)) {
            $query->andWhere(['PickingPlans.req_date <=' => $cond['req_date_to']]);
        }
        if ($this->hasSearchParams('req_user_id', $cond)) {
            $query->andWhere(['PickingPlans.req_user_id' => $cond['req_user_id']]);
        }
        if ($this->hasSearchParams('use_user_id', $cond)) {
            $query->andWhere(['PickingPlans.use_user_id' => $cond['use_user_id']]);
        }
        if ($this->hasSearchParams('arv_date_from', $cond)) {
            $query->andWhere(['PickingPlans.arv_date >=' => $cond['arv_date_from']]);
        }
        if ($this->hasSearchParams('arv_date_to', $cond)) {
            $query->andWhere(['PickingPlans.arv_date <=' => $cond['arv_date_to']]);
        }
        if ($this->hasSearchParams('name', $cond)) {
            $query->andWhere(['PickingPlans.name LIKE' => '%' . $cond['name'] . '%']);
        }
        if ($this->hasSearchParams('work_suser_id', $cond)) {
            $query->andWhere(['PickingPlans.work_suser_id' => $cond['work_suser_id']]);
        }
        if ($this->hasSearchParams('rcv_suser_id', $cond)) {
            $query->andWhere(['PickingPlans.rcv_suser_id' => $cond['rcv_suser_id']]);
        }
        if ($this->hasSearchParams('apply_no', $cond)) {
            $query->andWhere(['apply_no' => $cond['apply_no']]);
        }
        if ($this->hasSearchParams('serial_no', $cond)) {
            $query->andWhere(['Assets.serial_no' => $cond['serial_no']]);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 出庫可能な出庫予定詳細一覧を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 出庫可能な出庫予定詳細一覧（ResultSet or Array）
     */
    public function plansPicking($toArray = false)
    {
        $query = $this->modelTable->find('validAll')
            ->where( ['PickingPlans.plan_sts' => Configure::read('WNote.DB.Picking.PickingSts.before') ])
            ->order([
                'PickingPlans.plan_date'              => 'ASC',
                'PickingPlans.req_date'               => 'ASC',
                'PickingPlans.req_user_id'            => 'ASC',
                'PickingPlanDetails.apply_no'         => 'ASC',
                'PickingPlanDetails.product_id'       => 'ASC',
                'PickingPlanDetails.product_model_id' => 'ASC'
            ]);

        // 関連を追加
        $query = $this->_addAssociation($query);

        return ($toArray) ? $query->toArray() : $query->all();
    }


    /**
     * 指定されたクエリビルダに標準的な関連モデルを追加する
     *  
     * - - -
     * @param Cake\ORM\Query クエリビルダ
     * @return Cake\ORM\Query クエリビルダ
     */
    private function _addAssociation(Query $query) {
        return $query
            ->contain(['PickingPlans'])
            ->contain(['PickingPlans.PickingPlanPickingKbn' => function($q) {
                return $q
                    ->select(['id', 'name']);
            }])
            ->contain(['PickingPlans.PickingPlanSts' => function($q) {
                return $q
                    ->select(['id', 'name']);
            }])
            ->contain(['PickingPlans.PickingPlanReqOrganizations' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['PickingPlans.PickingPlanReqUsers' => function($q) {
                return $q
                    ->select(['id', 'sname', 'fname']);
            }])
            ->contain(['PickingPlans.PickingPlanDlvOrganizations' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['PickingPlans.PickingPlanDlvUsers' => function($q) {
                return $q
                    ->select(['id', 'sname', 'fname']);
            }])
            ->contain(['PickingPlans.PickingPlanUseOrganizations' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['PickingPlans.PickingPlanUseUsers' => function($q) {
                return $q
                    ->select(['id', 'sname', 'fname']);
            }])
            ->contain(['PickingPlans.PickingPlanRcvSusers' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['PickingPlans.PickingPlanTimeKbn' => function($q) {
                return $q
                    ->select(['id', 'name']);
            }])
            ->contain(['PickingPlans.PickingPlanWorkSusers' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['Categories' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['Classifications' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['Products' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['ProductModels' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['Assets' => function($q) {
                return $q
                    ->select(['id', 'asset_no', 'serial_no', 'remarks']);
            }])
            ->contain(['PickingPlanDetailReuseKbn' => function($q) {
                return $q
                    ->select(['id', 'name']);
            }])
            ->contain(['KittingPatterns' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }]);
    }

    /**
     * 出庫予定詳細を登録する
     *  
     * - - -
     * 
     * @param array $data 出庫予定詳細データ
     * @param array $plan 出庫予定データ
     * @param array $asset 資産データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($data, $plan, $asset)
    {
        $detail = $this->byPlanId($plan['id']);
        if (count($detail) > 0) {
            return parent::_invalid('既に出庫予定詳細が登録されているため、新たに出庫予定詳細を追加することはできません。', $detail);
        }

        $new = [];
        $new['domain_id']          = $plan['domain_id'];
        $new['picking_plan_id']    = $plan['id'];
        $new['picking_type']       = Configure::read('WNote.DB.Picking.PickingType.asset');
        $new['category_id']        = $plan['category_id'];
        $new['plan_count']         = '1';
        $new['detail_sts']         = Configure::read('WNote.DB.Picking.PickingSts.not');
        $new['apply_no']           = $plan['apply_no'];
        $new['kitting_pattern_id'] = $plan['kitting_pattern_id'];
        $new['reuse_kbn']          = $plan['reuse_kbn'];

        if ($asset) {
            $new['asset_id']          = $asset['id'];
            $new['classification_id'] = $asset['classification_id'];
            $new['product_id']        = $asset['product_id'];
            $new['product_model_id']  = $asset['product_model_id'];
            $new['asset_type']        = $asset['asset_type'];
        }

        return parent::add($new);
    }

    /**
     * 指定された出庫予定IDの出庫予定詳細（1件）を取得する
     *  
     * - - -
     * 
     * @param string $planId 出庫予定ID
     * @return \App\Model\Entity\PickingPlanDetail 出庫予定詳細
     */
    public function byPlanId($planId)
    {
        return $this->modelTable->find('valid')
            ->where([
                'picking_plan_id' => $planId
            ])
            ->first();
    }

    /**
     * 指定されたシリアル番号に対応する出庫可能な出庫予定詳細IDを取得する
     *  
     * - - -
     * 
     * @param string $serialNo シリアル番号
     * @return \App\Model\Entity\PickingPlanDetail|null 出庫予定詳細
     */
    public function getEnablePickingPlan($serialNo)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'detail_sts' => Configure::read('WNote.DB.Picking.PickingSts.before')
            ])
            ->innerJoinWith('Assets', function($q) use ($serialNo) {
                return $q
                    ->select(['id', 'serial_no'])
                    ->where(['Assets.serial_no' => $serialNo]);
            });

        if ($query->count() !== 1) return null;

        return $query->first();
    }

    /**
     * 出庫予定詳細を出庫依頼のデータより保存する
     *  
     * - - -
     * 
     * @param array $data 出庫予定詳細データ
     * @param array $plan 出庫予定データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function saveRequest($data, $plan)
    {
        $detail = $this->byPlanId($plan['id']);
        if (count($detail) == 0) {
            return parent::_invalid('出庫予定詳細が存在しないため、出庫予定詳細を更新することができません。', $detail);
        }

        $detail['category_id']        = $plan['category_id'];
        $detail['apply_no']           = $plan['apply_no'];
        $detail['kitting_pattern_id'] = $plan['kitting_pattern_id'];
        $detail['reuse_kbn']          = $plan['reuse_kbn'];

        return parent::save($detail->toArray());
    }

    /**
     * 出庫予定詳細を出庫予定一覧入力データより保存する
     *  
     * - - -
     * 
     * @param array $entry 出庫予定予定一覧入力データ
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function saveEntry($entry, $asset)
    {
        $detail = $this->modelTable->get($entry['plan_detail_id']);
        if (count($detail) === 0) {
            return parent::_invalid('出庫予定詳細が存在しないため、出庫予定詳細を更新することができません。', $detail);
        }

        $stock = $this->ModelStocks->stock($asset['id']);
        if (count($stock) === 0 || intVal($stock['stock_count']) < 1) {
            return parent::_invalid('在庫が存在しないため、出庫予定詳細を更新することができません。', $detail);
        }

        $detail['asset_id']           = $asset['id'];
        $detail['classification_id']  = $asset['classification_id'];
        $detail['product_id']         = $asset['product_id'];
        $detail['product_model_id']   = $asset['product_model_id'];
        $detail['asset_type']         = $asset['asset_type'];
        $detail['detail_sts']         = Configure::read('WNote.DB.Picking.PickingSts.work');

        return parent::save($detail->toArray());
    }

    /**
     * 出庫予定詳細を出庫可能な状態に更新する（ステータスを出庫前に更新）
     *  
     * - - -
     * 
     * @param string $id 出庫予定詳細ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addPicking($id)
    {
        $detail = parent::get($id);
        if (!detail || count($detail) === 0) {
            return parent::_invalid('出庫予定詳細が存在しないため、出庫登録することができません。', $detail);
        }

        $stock = $this->ModelStocks->stock($detail['asset_id']);
        if (count($stock) === 0 || intVal($stock['stock_count']) < 1) {
            return parent::_invalid('在庫が存在しないため、出庫登録することができません。', $detail);
        }

        $detail['detail_sts'] = Configure::read('WNote.DB.Picking.PickingSts.before');
        $result = parent::save($detail->toArray());

        $resultPlan = $this->ModelPickingPlans->addPicking($detail['picking_plan_id']);
        if (!$resultPlan['result']) {
            return $resultPlan;
        }

        return $result;
    }

    /**
     * 出庫予定詳細を出庫に更新する
     *  
     * - - -
     * 
     * @param array $picking 出庫情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateComplete($picking)
    {
        $detail = parent::get($picking['picking_plan_detail_id']);
        if (!$detail) {
            return parent::_invalid(['message'=> '指定された出庫情報に対する出庫予定詳細がありません。', 'data' => ['method' => __METHOD__, 'picking' => $picking]]);
        }

        // 出庫明細状況更新
        $detail['detail_sts'] = Configure::read('WNote.DB.Picking.PickingSts.complete');
        $updateDetail = parent::save($detail->toArray());
        if (!$updateDetail['result']) {
            return $updateDetail;
        }

        // 出庫予定の出庫状況更新を行う
        $updatePlan = $this->ModelPickingPlans->updateComplete($detail['picking_plan_id']);
        if (!$updatePlan['result']) {
            return $updatePlan;
        }

        return $updateDetail;
    }

    /**
     * 指定された出庫予定の出庫予定詳細を取消する
     *  
     * - - -
     * 
     * @param string $planId 出庫予定ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function cancel($planId)
    {
        $detail = $this->byPlanId($planId);
        if (count($detail) === 0) {
            return parent::_invalid('出庫予定詳細が存在しないため、出庫予定詳細を取消することができません。', $detail);
        }

        $detail['detail_sts'] = Configure::read('WNote.DB.Picking.PickingSts.cancel');

        return parent::save($detail->toArray());
    }

    /**
     * 指定された取消状態の出庫予定の出庫予定詳細を取消解除する（未出庫状態に戻す）
     *  
     * - - -
     * 
     * @param string $planId 出庫予定ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function cancelRestore($planId)
    {
        $detail = $this->byPlanId($planId);
        if (count($detail) === 0) {
            return parent::_invalid('出庫予定詳細が存在しないため、出庫予定詳細を取消することができません。', $detail);
        }

        $detail['detail_sts']        = Configure::read('WNote.DB.Picking.PickingSts.not');
        $detail['asset_id']          = null;
        $detail['classification_id'] = null;
        $detail['product_id']        = null;
        $detail['product_model_id']  = null;
        $detail['asset_type']        = null;

        return parent::save($detail->toArray());
    }

    /**
     * 指定された取消状態の出庫予定の出庫予定詳細を取消確定する
     *  
     * - - -
     * 
     * @param string $planId 出庫予定ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function cancelFix($planId)
    {
        $detail = $this->byPlanId($planId);
        if (count($detail) === 0) {
            return parent::_invalid('出庫予定詳細が存在しないため、出庫予定詳細を取消することができません。', $detail);
        }

        $detail['detail_sts'] = Configure::read('WNote.DB.Picking.PickingSts.cancelfix');

        return parent::save($detail->toArray());
    }

}
