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
 * 出庫（Pickings）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelPickingsComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    // public $components = [];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'Pickings';
        parent::initialize($config);
    }

    /**
     * 本日の出庫数合計を取得する
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function countToday()
    {
        $query = $this->modelTable->find('validAll');
        $query
            ->select(['sum_picking_count' => $query->func()->sum('picking_count')])
            ->andWhere(['picking_date' => $this->today()])
            ->group(['picking_date']);

        $result = $query->first();
        if (!$result || count($result) === 0) return 0;

        return intVal($result['sum_picking_count']);
    }

    /**
     * 出庫を新規に登録する
     *  
     * - - -
     * @param array $entry 出庫入力データ
     * @param array $plan 出庫予定データ
     * @param array $detail 出庫予定詳細データ
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($entry, $plan, $detail, $asset) {
        $picking = [];
        $picking['domain_id']              = $this->current();
        $picking['picking_kbn']            = $plan['picking_kbn'];
        $picking['asset_type']             = $asset['asset_type'];
        $picking['picking_plan_id']        = $plan['id'];
        $picking['picking_plan_detail_id'] = $detail['id'];
        $picking['picking_date']           = $entry['picking_date'];
        $picking['picking_suser_id']       = $entry['picking_suser_id'];
        $picking['picking_count']          = '1';
        $picking['delivery_company_id']    = $entry['delivery_company_id'];
        $picking['voucher_no']             = $entry['voucher_no'];
        $picking['remarks']                = $entry['remarks'];

        return parent::add($picking);
    }
}
