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
 * 入庫（Instocks）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelInstocksComponent extends AppModelComponent
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
        $config['modelName'] = 'Instocks';
        parent::initialize($config);
    }

    /**
     * 本日の入庫数合計を取得する
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function countToday()
    {
        $query = $this->modelTable->find('validAll');
        $query
            ->select(['sum_instock_count' => $query->func()->sum('instock_count')])
            ->andWhere(['instock_date' => $this->today()])
            ->group(['instock_date']);

        $result = $query->first();
        if (!$result || count($result) === 0) return 0;

        return intVal($result['sum_instock_count']);
    }

    /**
     * 入庫を登録する
     *  
     * - - -
     * 
     * @param array $instock     入庫情報
     * @param array $serials     入力シリアル
     * @param string $inputCount 入力数
     * @param string $assetId    資産ID
     * @param array $plan        入庫予定情報
     * @param array $planDetail  入庫予定詳細情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($instock, $serials, $inputCount, $assetId, $plan, $planDetail)
    {
        $count = 1; // 資産入力時は固定で「1」
        if (!assetId || assetId === '') { 
            $count = (count($serials) === 0) ? $inputCount : count($serials);
        }

        $instock['domain_id']     = $this->current();
        $instock['instock_kbn']   = $plan['instock_kbn'];
        $instock['asset_type']    = $planDetail['asset_type'];
        $instock['instock_count'] = $count;

        return parent::add($instock);
    }

    /**
     * 入庫予定詳細に対する入庫数量を取得する
     *  
     * - - -
     * 
     * @param integer $instockPlanDetailId 入庫予定詳細ID
     * @return integer 入庫数量
     */
    public function instockCountByPlanDetailId($instockPlanDetailId)
    {
        $query = $this->modelTable->find('valid')
            ->select([
                'instock_plan_detail_id',
                'sum_instock_count' => function($q) {
                    return $q->func()->sum('instock_count');
                }
            ])
            ->where([
                'instock_plan_detail_id' => $instockPlanDetailId
            ]);

        $count = $query->count();
        if ($count ==0) {
            return $count;
        }

        $instockCount = $query->first();

        return intval($instockCount['sum_instock_count']);
    }

}
