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
 * 入庫詳細（InstockDetails）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelInstockDetailsComponent extends AppModelComponent
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
        $config['modelName'] = 'InstockDetails';
        parent::initialize($config);
    }

    /**
     * 新規入庫の詳細を登録する
     *  
     * - - -
     * 
     * @param array $instock 入庫情報
     * @param array $assets 資産情報（複数）
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($instock, $assets)
    {
        $data = [];
        $data['domain_id']  = $instock['domain_id'];
        $data['instock_id'] = $instock['id'];

        $results = [];
        $assets = (count($assets) == 1 && gettype($assets) === 'object') ? [$assets] : $assets;
        foreach($assets as $asset) {
            $data['asset_id']  = $asset['id'];
            $data['serial_no'] = $asset['serial_no'];
            $data['asset_no']  = $asset['asset_no'];
            $result = parent::add($data);
            if (!$result['result']) {
                return $result;
            }
            $results[] = $result['data'];
        }

        return $this->_result(true, $results, false);
    }

    /**
     * 入庫詳細を検索する
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
                'id', 'instock_id', 'asset_id'
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
                'Instocks' => function($q) { return $q->select([
                        'id', 'instock_kbn', 'instock_plan_id', 'instock_plan_detail_id', 'instock_date',
                        'instock_suser_id', 'confirm_suser_id', 'instock_count', 'delivery_company_id', 'voucher_no', 'remarks'
                    ]); }
            ])
            ->contain([
                'Instocks.InstocksInstockKbn' => function($q) { return $q->select([
                        'id', 'name'
                    ]); }
            ])
            ->contain([
                'Instocks.ConfirmSusers' => function($q) { return $q->select([
                        'id', 'kname'
                    ]); }
            ])
            ->contain([
                'Instocks.InstockSusers' => function($q) { return $q->select([
                        'id', 'kname'
                    ]); }
            ])
            ->contain([
                'Instocks.InstocksAssetType' => function($q) { return $q->select([
                        'id', 'nkey', 'nid', 'name'
                    ]); }
            ])
            ->contain([
                'Instocks.InstocksInstockKbn' => function($q) { return $q->select([
                        'id', 'nkey', 'nid', 'name'
                    ]); }
            ])
            ->contain([
                'Instocks.InstockPlans' => function($q) { return $q->select([
                        'id', 'name', 'plan_sts', 'plan_date'
                    ]); }
            ])
            ->order([
                'Instocks.instock_date'     => 'DESC',
                'Instocks.voucher_no'       => 'ASC',
                'InstockDetails.asset_no'   => 'ASC', 
                'InstockDetails.serial_no'  => 'ASC', 
                'InstockDetails.asset_id'   => 'ASC' 
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
        if ($this->hasSearchParams('instock_date_from', $cond)) {
            $query->where(['Instocks.instock_date >=' => $cond['instock_date_from']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('instock_date_to', $cond)) {
            $query->where(['Instocks.instock_date <=' => $cond['instock_date_to']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('instock_suser_id', $cond)) {
            $query->where(['Instocks.instock_suser_id' => $cond['instock_suser_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('confirm_suser_id', $cond)) {
            $query->where(['Instocks.confirm_suser_id' => $cond['confirm_suser_id']]);
            $hasCondition = true;
        }
        if (array_key_exists('not_confirmation', $cond) && $cond['not_confirmation'] == '1') {
            $query->where(['Instocks.confirm_suser_id IS' => null]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('voucher_no', $cond)) {
            $query->where(['Instocks.voucher_no' => $cond['voucher_no']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('remarks', $cond)) {
            $query->where(['Instocks.remarks like' => '%' . $cond['remarks'] . '%']);
            $hasCondition = true;
        }

        if (!$hasCondition) {
            $query = $this->modelTable->find('valid')->where(['1 = 0']); // 検索しない
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

}
