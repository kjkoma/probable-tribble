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
     * 入庫予定一覧（新規）を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 入庫予定（新規）一覧（ResultSet or Array）
     */
    public function listNew($toArray = false)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'instock_kbn'    => Configure::read('WNote.DB.Instock.InstockKbn.new'),
                'instock_sts <>' => Configure::read('WNote.DB.Instock.InstockSts.complete'),
            ])
            ->contain('[InstockPlansKbn]')
            ->contain('[InstockPlansSts]');

        return ($toArray) ? $query->toArray() : $query->all();
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
                'instock_sts <>' => Configure::read('WNote.DB.Instock.InstockSts.complete'),
            ])
            ->contain(['InstockPlansKbn'])
            ->contain(['InstockPlansSts']);

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
        if (array_key_exists('instock_sts', $cond)) {
            $query->where(['instock_sts IN' => $cond['instock_sts']]);
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
$this->log($query);
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

}
