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
 * 入庫予定詳細（InstockPlanDetails）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelInstockPlanDetailsComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['ModelInstockPlans', 'ModelProducts'];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'InstockPlanDetails';
        parent::initialize($config);
    }

    /**
     * 入庫予定詳細を取得する
     *  
     * - - -
     * @param integer $detailId 入庫予定詳細ID
     * @return \App\Model\Entity\InstockPlanDetail 入庫予定詳細
     */
    public function detail($detailId)
    {
        return $this->modelTable->find('valid')
            ->where([
                'InstockPlanDetails.id' => $detailId
            ])
            ->contain(['Products' => function($q) {
                return $q
                    ->select(['id', 'kname', 'maker_id', 'classification_id', 'asset_type'])
                    ->contain(['Companies' => function($q1) {
                        return $q1->select(['id', 'kname']);
                    }]);
            }])
            ->contain(['ProductModels' => function($q) {
                return $q->select(['id', 'kname', 'product_id']);
            }])
            ->contain(['Classifications' => function($q) {
                return $q
                    ->select(['id', 'kname'])
                    ->contain(['Categories' => function($q1) {
                        return $q1->select(['id', 'kname']);
                    }]);
            }])
           ->contain(['InstockPlanDetailsSts' => function($q) {
                return $q->select(['name']);
            }])
           ->contain(['Instocks' => function($q) {
                return $q
                    ->select([
                        'instock_plan_detail_id',
                        'sum_instock_count' => $q->func()->sum('instock_count')
                    ])
                    ->group(['instock_plan_detail_id']);
            }])
            ->first();
    }

    /**
     * 入庫予定詳細一覧を取得する
     *  
     * - - -
     * @param integer $planId 入庫予定ID
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 入庫予定詳細一覧（ResultSet or Array）
     */
    public function details($planId, $toArray = false)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'instock_plan_id' => $planId
            ])
            ->contain(['Products' => function($q) {
                return $q
                    ->select(['id', 'kname', 'maker_id', 'classification_id', 'asset_type'])
                    ->contain(['Companies' => function($q1) {
                        return $q1->select(['id', 'kname']);
                    }]);
            }])
            ->contain(['ProductModels' => function($q) {
                return $q->select(['id', 'kname', 'product_id']);
            }])
            ->contain(['Classifications' => function($q) {
                return $q
                    ->select(['id', 'kname'])
                    ->contain(['Categories' => function($q1) {
                        return $q1->select(['id', 'kname']);
                    }]);
            }])
           ->contain(['InstockPlanDetailsSts' => function($q) {
                return $q->select(['name']);
            }])
           ->contain(['Instocks' => function($q) {
                return $q
                    ->select([
                        'instock_plan_detail_id',
                        'sum_instock_count' => $q->func()->sum('instock_count')
                    ])
                    ->group(['instock_plan_detail_id']);
            }]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * シリアル／資産管理番号指定で未入庫、一部入庫の入庫予定詳細一覧を取得する
     *  
     * - - -
     * @param string $cond シリアル番号 or 資産管理番号
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 入庫予定詳細一覧（ResultSet or Array）
     */
    public function detailsAsset($cond, $toArray = false)
    {
        $query = $this->modelTable->find('validAll')
            ->contain(['InstockPlans' => function($q) {
                return $q
                    ->where(['InstockPlans.plan_sts' => Configure::read('WNote.DB.Instock.InstockSts.not')])
                    ->orwhere(['InstockPlans.plan_sts' => Configure::read('WNote.DB.Instock.InstockSts.part')]);
            }])
            ->contain(['InstockPlans.InstockPlansKbn' => function($q) {
                return $q->select(['id', 'name']);
            }])
            ->contain(['Classifications' => function($q) {
                return $q->select(['id', 'kname']);
            }])
            ->contain(['Products' => function($q) {
                return $q->select(['id', 'kname']);
            }])
            ->innerJoinWith('Assets', function($q) use ($cond) {
                return $q
                    ->select(['id', 'asset_no', 'serial_no'])
                    ->andwhere(['Assets.serial_no'  => $cond])
                    ->orWhere(['Assets.asset_no' => $cond]);
            })
            ->order([
                'InstockPlans.plan_date'   => 'ASC',
                'InstockPlans.instock_kbn' => 'ASC'
            ]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 指定された入庫予定に対するソートされた入庫予定詳細一覧を取得する
     *  
     * - - -
     * @param integer $planId 入庫予定ID
     * @return array 入庫予定詳細一覧（ResultSet or Array）
     */
    public function byPlanId($planId, $toArray = false)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'instock_plan_id' => $planId
            ]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 入庫予定詳細(新規)を登録する
     *  
     * - - -
     * 
     * @param array $detail 入庫予定詳細情報
     * @param array $plan 入庫予定情報
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($detail, $plan, $asset)
    {
        $product = $this->ModelProducts->get($detail['product_id']);
        if (!$product) {
            return parent::_invalid('指定された入庫予定詳細の製品情報がありません。', ['method' => __METHOD__, 'data' => $detail]);
        }

        $instockType = Configure::read('WNote.DB.Instock.InstockType.new');
        $instockType = ($plan['instock_kbn'] == Configure::read('WNote.DB.Instock.InstockKbn.new')) ? $instockType : Configure::read('WNote.DB.Instock.InstockType.asset');

        $detail['domain_id']    = $this->current();
        $detail['instock_type'] = $instockType;
        $detail['asset_type']   = $product['asset_type'];
        $detail['detail_sts']   = Configure::read('WNote.DB.Instock.InstockSts.not');
        $detail['plan_count']   = ($detail['plan_count'] === '') ? '1' : $detail['plan_count'];

        if ($asset) {
            $detail['asset_id'] = $asset['id'];
        }

        return parent::add($detail);
    }

    /**
     * 入庫予定詳細(既存資産より)を登録する
     *  
     * - - -
     * 
     * @param integer $planId 入庫予定ID
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addByAsset($planId, $asset)
    {
        $detail = [];
        $detail['domain_id']         = $this->current();
        $detail['instock_plan_id']   = $planId;
        $detail['instock_type']      = Configure::read('WNote.DB.Instock.InstockType.asset');
        $detail['asset_id']          = $asset['id'];
        $detail['classification_id'] = $asset['classification_id'];
        $detail['product_id']        = $asset['product_id'];
        $detail['product_model_id']  = $asset['product_model_id'];
        $detail['asset_type']        = $asset['asset_type'];
        $detail['plan_count']        = '1';
        $detail['detail_sts']        = Configure::read('WNote.DB.Instock.InstockSts.not');

        return parent::add($detail);
    }

    /**
     * 入庫予定詳細(既存資産より)を保存する
     *  
     * - - -
     * 
     * @param integer $planDetailId 入庫予定詳細ID
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function saveByAsset($planDetailId, $asset)
    {
        $detail = $this->modelTable->get($planDetailId);
        if (!$detail || count($detail) == 0) {
            return $this->_invalid('入庫予定詳細（交換・修理の出庫時）を更新できませんでした。', $planId);
        }
        $detail['asset_id']          = $asset['id'];
        $detail['classification_id'] = $asset['classification_id'];
        $detail['product_id']        = $asset['product_id'];
        $detail['product_model_id']  = $asset['product_model_id'];
        $detail['asset_type']        = $asset['asset_type'];

        return parent::save($detail->toArray());
    }

    /**
     * 入庫予定詳細(新規)を画面入力内容より更新する
     *  
     * - - -
     * 
     * @param array $detail 入庫予定詳細情報
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function update($detail, $asset)
    {
        if ($asset) {
            $detail['asset_id'] = $asset['id'];
        }

        return parent::save($detail);
    }

    /**
     * 入庫予定詳細の入庫状況を更新する
     *  
     * - - -
     * @param array $instock 入庫情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateInstock($instock)
    {
        $detail = parent::get($instock['instock_plan_detail_id']);
        if (!$detail) {
            return parent::_invalid(['message'=> '指定された入庫情報に対する入庫予定詳細がありません。', 'data' => $instock]);
        }

        // 入庫件数と予定件数
        $instock_count = is_numeric($instock['instock_count']) ? intVal($instock['instock_count']) : 0;
        $plan_count    = is_numeric($detail['plan_count']) ? intVal($detail['plan_count']) : 0;

        // 入庫明細状況判別
        $sts = Configure::read('WNote.DB.Instock.InstockSts.not');
        if ($instock_count > 0 && $instock_count < $plan_count) {
            $sts = Configure::read('WNote.DB.Instock.InstockSts.part');
        }
        if ($instock_count > 0 && $instock_count >= $plan_count) {
            $sts = Configure::read('WNote.DB.Instock.InstockSts.complete');
        }

        // 入庫明細状況更新
        $detail['detail_sts'] = $sts;
        $result = parent::save($detail->toArray());

        // 予定の入庫状況更新を行う
        if ($result['result']) {
            $updatePlan = $this->ModelInstockPlans->updateStatus($detail['instock_plan_id']);
            if (!$updatePlan['result']) {
                return $updatePlan;
            }
        }

        return $result;
    }
}
