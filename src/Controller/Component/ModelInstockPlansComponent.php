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
 * 入庫予定（InstockPlans）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelInstockPlansComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['ModelInstockPlanDetails', 'ModelAssets'];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'InstockPlans';
        parent::initialize($config);
    }

    /**
     * 入庫予定一覧（新規・返却）を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 入庫予定（新規・返却）一覧（ResultSet or Array）
     */
    public function listNew($toArray = false)
    {
        $query = $this->modelTable->find('valid');
        $query = $this->_makeAssociation($query);
        $query
            ->andWhere(['instock_kbn IN' => [Configure::read('WNote.DB.Instock.InstockKbn.new'), Configure::read('WNote.DB.Instock.InstockKbn.back')]])
            ->andWhere(['plan_sts <>' => Configure::read('WNote.DB.Instock.InstockSts.complete')]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 入庫可能な入庫予定一覧を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 入庫可能な入庫予定一覧（ResultSet or Array）
     */
    public function listInstock($toArray = false)
    {
        $query = $this->modelTable->find('valid');
        $query = $this->_makeAssociation($query);
        $query->andWhere(['plan_sts <>' => Configure::read('WNote.DB.Instock.InstockSts.complete')]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 当日の入庫予定一覧を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 当日の入庫予定一覧（ResultSet or Array）
     */
    public function listToday($toArray = false)
    {
        $query = $this->modelTable->find('valid');
        $query = $this->_makeAssociation($query);
        $query->andWhere(['plan_sts <>' => Configure::read('WNote.DB.Instock.InstockSts.cancelfix')]);
        $query->andWhere(['plan_date' => $this->today()]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 入庫可能な入庫予定一覧を取得する
     *  
     * - - -
     * @param \Cake\ORM\Query $query クエリビルダ
     * @return \Cake\ORM\Query クエリビルダ
     */
    private function _makeAssociation($query)
    {
        $query = $query
            ->contain(['InstockPlansKbn'])
            ->contain(['InstockPlansSts'])
            ->contain(['InstockPlanDetails' => function($q) {
                return $q
                    ->select([
                        'instock_plan_id',
                        'sum_plan_count' => $q->func()->sum('plan_count')
                    ])
                    ->group(['instock_plan_id']);
            }])
            ->contain(['Instocks' => function($q) {
                return $q
                    ->select([
                        'instock_plan_id',
                        'sum_instock_count' => $q->func()->sum('instock_count')
                    ])
                    ->group(['instock_plan_id']);
            }]);

        return $query;
    }

    /**
     * 入庫予定一覧を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 入庫予定一覧（ResultSet or Array）
     */
    public function list($toArray = false)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'plan_sts <>' => Configure::read('WNote.DB.Instock.InstockSts.complete'),
            ])
            ->contain(['InstockPlansKbn'])
            ->contain(['InstockPlansSts'])
            ->contain(['InstockPlanDetails' => ['Assets']]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 入庫予定を検索する
     *  
     * - - -
     * @param array $cond 検索条件
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 新規予定全一覧（ResultSet or Array）
     */
    public function search($cond, $toArray = false)
    {
        $query = $this->modelTable->find('valid')
            ->contain(['InstockPlansKbn'])
            ->contain(['InstockPlansSts'])
            ->contain(['InstockPlanDetails' => ['Assets']]);

        if (array_key_exists('instock_kbn', $cond)) {
            $query->where(['instock_kbn IN' => $cond['instock_kbn']]);
        }
        if (array_key_exists('plan_sts', $cond)) {
            $query->where(['plan_sts IN' => $cond['plan_sts']]);
        }
        if (array_key_exists('plan_date_from', $cond)) {
            $query->where(['plan_date >=' => $cond['plan_date_from']]);
        }
        if (array_key_exists('plan_date_to', $cond)) {
            $query->where(['plan_date <=' => $cond['plan_date_to']]);
        }
        if (array_key_exists('remarks', $cond)) {
            $query->where(['remarks like' => '%' . $cond['remarks'] . '%']);
        }
        if (array_key_exists('product_id', $cond)) {
            $query->where(['InstockPlanDetails.product_id' => $cond['product_id']]);
        }
        if (array_key_exists('serial_no', $cond)) {
            $query->where(['InstockPlanDetails.Assets.serial_no' => $cond['serial_no']]);
        }
        if (array_key_exists('asset_no', $cond)) {
            $query->where(['InstockPlanDetails.Assets.asset_no' => $cond['asset_no']]);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 入庫予定詳細（新規）を取得する
     *  
     * - - -
     * @param integer $plan_id  入庫予定ID
     * @return array 入庫詳細（新規）
     */
    public function detailNew($plan_id)
    {
        $query = $this->modelTable->find('valid')
            ->where(['id' => $plan_id])
            ->contain(['InstockPlansKbn'])
            ->contain(['InstockPlansSts'])
            ->contain(['InstockPlanDetails']);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 入庫予定詳細を取得する
     *  
     * - - -
     * @param integer $plan_id  入庫予定ID
     * @return array 入庫詳細
     */
    public function detail($plan_id)
    {
        $query = $this->modelTable->find('valid')
            ->where(['id' => $plan_id])
            ->contain(['InstockPlansKbn'])
            ->contain(['InstockPlansSts'])
            ->contain(['InstockPlanDetails']);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 入庫予定（新規）を登録する
     *  
     * - - -
     * @param mixed $plan 新規入庫予定データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($plan) {
        $plan['plan_sts']    = Configure::read('WNote.DB.Instock.InstockSts.not');

        return parent::add($plan);
    }

    /**
     * 入庫予定（交換時）を登録する
     *  
     * - - -
     * @param string $planDate 入庫予定日
     * @param array  $asset 入庫資産情報
     * @param string $remarks 入庫備考
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addByExchange($planDate, $asset, $remarks) {
        $plan = [];
        $plan['domain_id']   = $this->current();
        $plan['instock_kbn'] = Configure::read('WNote.DB.Instock.InstockKbn.exchange');
        $plan['plan_date']   = $planDate;
        $plan['name']        = $this->createNameByAsset($asset, '交換');
        $plan['plan_sts']    = Configure::read('WNote.DB.Instock.InstockSts.not');
        $plan['remarks']     = $remarks;

        return parent::add($plan);
    }

    /**
     * 入庫予定（修理時）を登録する
     *  
     * - - -
     * @param string $planDate 入庫予定日
     * @param array  $asset 入庫資産情報
     * @param string $remarks 入庫備考
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addByRepair($planDate, $asset, $remarks) {
        $plan = [];
        $plan['domain_id']   = $this->current();
        $plan['instock_kbn'] = Configure::read('WNote.DB.Instock.InstockKbn.repair');
        $plan['plan_date']   = $planDate;
        $plan['name']        = $this->createNameByAsset($asset, '修理');
        $plan['plan_sts']    = Configure::read('WNote.DB.Instock.InstockSts.not');
        $plan['remarks']     = $remarks;

        return parent::add($plan);
    }

    /**
     * 入庫予定（修理時）を登録する
     *  
     * - - -
     * @param array  $asset 入庫資産情報
     * @param string $prefix 件名の最初につけるプリフィックス
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function createNameByAsset($asset, $prefix) {
        $keyName = ($asset['asset_no'] != '') ? $asset['asset_no'] : $asset['serial_no'];
        $keyName = ($keyName != '') ? '(' . $keyName . ')' : '';

        return '[' . $prefix . ']' . $keyName . $asset['kname'];
    }

    /**
     * 入庫予定の入庫状況を更新する
     *  
     * - - -
     * @param integer $planId 入庫予定ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateStatus($planId) {
        // 入庫予定
        $plan = $this->get($planId);
        if (!$plan) {
            return $this->_invalid(['message' => '指定された入庫情報がありません。', 'data' => ['plan_id' => $planId]]);
        }
        // 入庫予定詳細
        $details = $this->ModelInstockPlanDetails->byPlanId($plan['id']);

        // 未入庫/一部入庫/入庫の判定
        $sts = ['not' => false, 'part' => false, 'complete' => false, 'result' => null];
        foreach($details as $detail) {
            if ($detail['detail_sts'] == Configure::read('WNote.DB.Instock.InstockSts.not')) {
                $sts['not'] = true;
            }
            if ($detail['detail_sts'] == Configure::read('WNote.DB.Instock.InstockSts.part')) {
                $sts['part'] = true;
            }
            if ($detail['detail_sts'] == Configure::read('WNote.DB.Instock.InstockSts.complete')) {
                $sts['complete'] = true;
            }
        }

        $sts['result'] = (count($details) == 0) ? Configure::read('WNote.DB.Instock.InstockSts.not') : Configure::read('WNote.DB.Instock.InstockSts.complete');
        $sts['result'] = ($sts['part']) ? Configure::read('WNote.DB.Instock.InstockSts.part') : $sts['result'];
        $sts['result'] = ($sts['not'] && $sts['complete']) ? Configure::read('WNote.DB.Instock.InstockSts.part') : $sts['result'];
        $sts['result'] = ($sts['not'] && !$sts['part'] && !$sts['complete']) ? Configure::read('WNote.DB.Instock.InstockSts.not') : $sts['result'];

        if ($plan['plan_sts'] != $sts['result']) {
            $plan['plan_sts'] = $sts['result'];
            return parent::save($plan->toArray());
        }

        return $this->_result(true, $plan);
    }

    /**
     * 入庫予定（交換・修理時）を更新する
     *  
     * - - -
     * @param string $planId 入庫予定ID
     * @param string $planDate 入庫予定日
     * @param string $remarks 入庫備考
     * @param array  $asset    資産情報
     * @param string $prefix   件名に付与するプリフィックス
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function saveByPicking($planId, $planDate, $remarks, $asset, $prefix) {
        $plan = $this->modelTable->get($planId);
        if (!$plan || count($plan) == 0) {
            return $this->_invalid('入庫予定（交換・修理の出庫時）を更新できませんでした。', $planId);
        }
        $plan['plan_date']   = $planDate;
        $plan['name']        = $this->createNameByAsset($asset, $prefix);
        $plan['remarks']     = $remarks;

        return parent::save($plan->toArray());
    }

    /**
     * 入庫予定を取消可能かどうか検証する
     *  
     * - - -
     * @param string $planId 入庫予定ID
     * @return boolean true: 取消可能/false: 取消不可
     */
    public function validateCancel($planId) {
        // 入庫予定を取得
        $plan = $this->modelTable->get($planId);

        // ステータスチェック
        $validate = true;
        if (!$plan || count($plan) == 0 || $plan['plan_sts'] != Configure::read('WNote.DB.Instock.InstockSts.not')) {
            $validate = false;
        }

        return $validate;
    }
}
