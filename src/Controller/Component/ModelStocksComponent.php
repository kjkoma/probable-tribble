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
 * 在庫（Stocks）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelStocksComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['ModelStockHistories'];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'Stocks';
        parent::initialize($config);
    }

    /**
     * 現在のPCの総在庫件数を取得する
     *  
     * - - -
     * 
     * @return integer 資産件数（PC）
     */
    public function countPc()
    {
        $query = $this->modelTable->find('validAll')
            ->select(['id', 'asset_id', 'stock_count'])
            ->andWhere(['stock_count >' => 0])
            ->matching('Assets', function($q) {
                return $q
                    ->where(['Assets.asset_type' => Configure::read('WNote.DB.Assets.AssetType.asset')])
                    ->where(['Assets.asset_sts NOT IN' => [Configure::read('WNote.DB.Assets.AssetSts.abrogate'), Configure::read('WNote.DB.Assets.AssetSts.lost')]]);
            })
            ->matching('Assets.Classifications.Categories', function($q) {
                return $q->where(['Categories.id IN' => Configure::read('WNote.DB.Categories.pc')]);
            });

            return $query->count();
    }

    /**
     * 資産の在庫を取得する
     *  
     * - - -
     * 
     * @param array $assetId 資産ID
     * @param boolean $includeAssociation 関連有無（true: 関連を含む、false: 関連を含まない）
     * @return array 資産情報
     */
    public function stock($assetId, $includeAssociation = false)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'asset_id' => $assetId
            ]);

        if ($includeAssociation) {
            $query
                ->contain([
                    'StocksModifiedSuserName' => function($q) {
                        return $q->select(['id', 'kname']);
                    }
                ]);
        }

        return $query->first();
    }

    /**
     * 現在在庫の棚卸登録用クエリを取得する
     *  
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @return \Cake\ORM\Query クエリ
     */
    public function stocktakeTargetsQuery($stocktakeId)
    {
        $query = $this->modelTable->find('valid');
        $query->select([
                'domain_id'          => $this->current(),
                'stocktake_id'       => $stocktakeId,
                'Assets__id'         => 'Assets.id',
                'Assets__asset_type' => 'Assets.asset_type',
                'stock_count'        => 'Stocks.stock_count',
                'dsts'               => 'Stocks.dsts',
                'created_at'         => $query->func()->now(),
                'created_user'       => $this->user(),
                'modified_at'        => $query->func()->now(),
                'modified_user'      => $this->user()
            ])
            ->where([
                'stock_count >' => 0
            ])
            ->contain([
                'Assets' => function($q) {
                    return $q->select(['id', 'asset_type']);
                }
            ]);

        return $query;
    }

    /**
     * 資産集計を取得する
     *  
     * - - -
     * 
     * @param array $cond 検索条件
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 在庫集計結果（ResultSet or Array）
     */
    public function summary($cond, $toArray = false)
    {
        $query = $this->modelTable->find('all');
        $query
            ->contain([
                'Assets.Classifications.Categories' => function($q) {
                    return $q->select(['id', 'kname']);
                }
            ])
            ->contain([
                'Assets.Classifications' => function($q) {
                    return $q->select(['id', 'kname']);
                }
            ])
            ->contain([
                'Assets.Companies' => function($q) {
                    return $q->select(['id', 'kname']);
                }
            ])
            ->contain([
                'Assets.Products' => function($q) {
                    return $q->select(['id', 'kname']);
                }
            ])
            ->contain([
                'Assets.ProductModels' => function($q) {
                    return $q->select(['id', 'kname']);
                }
            ])
            ->contain([
                'Assets.AssetStsName' => function($q) {
                    return $q->select(['name']);
                }
            ])
            ->contain([
                'Assets.AssetSubStsName' => function($q) {
                    return $q->select(['name']);
                }
            ])
            ->select([
                'category_id'         => 'Categories.id',
                'category_name'       => $query->func()->max('Categories.kname'),
                'classification_id'   => 'Classifications.id',
                'classification_name' => $query->func()->max('Classifications.kname'),
                'maker_id'            => 'Companies.id',
                'maker_name'          => $query->func()->max('Companies.kname'),
                'product_id'          => 'Products.id',
                'product_name'        => $query->func()->max('Products.kname'),
                'product_model_id'    => 'ProductModels.id',
                'product_model_name'  => $query->func()->max('ProductModels.kname'),
                'asset_sts_name'      => 'AssetStsName.name',
                'asset_sub_sts_name'  => 'AssetSubStsName.name',
                'sum_stock_count'     => $query->func()->sum('stock_count')
            ])
            ->where([
                'Stocks.stock_count >' => 0,
                'Stocks.dsts'          => Configure::read('WNote.DB.Dsts.valid')
            ])
            ->group([
                'category_id',
                'classification_id',
                'maker_id',
                'product_id',
                'product_model_id',
                'AssetStsName.name',
                'AssetSubStsName.name'
            ])
            ->order([
                'Categories.kname',
                'Classifications.kname',
                'Companies.kname',
                'Products.kname',
                'ProductModels.kname',
                'AssetStsName.name',
                'AssetSubStsName.name'
            ]);

        if ($this->hasSearchParams('classification_id', $cond)) {
            $query->where(['Assets.classification_id IN' => $cond['classification_id']]);
        }
        if ($this->hasSearchParams('maker_id', $cond)) {
            $query->where(['Assets.maker_id' => $cond['maker_id']]);
        }
        if ($this->hasSearchParams('product_id', $cond)) {
            $query->where(['Assets.product_id' => $cond['product_id']]);
        }
        if ($this->hasSearchParams('product_model_id', $cond)) {
            $query->where(['Assets.product_model_id' => $cond['product_model_id']]);
        }
        if ($this->hasSearchParams('asset_sts', $cond)) {
            $query->where(['Assets.asset_sts' => $cond['asset_sts']]);
        }
        if ($this->hasSearchParams('asset_sub_sts', $cond)) {
            $query->where(['Assets.asset_sub_sts' => $cond['asset_sub_sts']]);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 入庫情報より在庫登録／更新を行う
     *  
     * - - -
     * 
     * @param array $assetId    資産ID（新規以外の単品入庫時に指定）
     * @param array $assets     資産情報（新規に入庫した資産 - 複数）
     * @param array $instock    入庫情報
     * @param array $planDetail 入庫予定詳細情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function instock($assetId, $assets, $instock, $planDetail)
    {
        // 新規在庫登録
        if (!$assetId || $assetId === '') {
            return $this->addNew($planDetail, $instock, $assets);
        }

        // 在庫更新
        return $this->updateInstock($assetId, $instock);
    }

    /**
     * 画面による資産登録時の在庫を登録する
     *  
     * - - -
     * 
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addEntry($asset)
    {
        $stock = [];
        $stock['domain_id'] = $this->current();
        $stock['asset_id']  = $asset['id'];
        $stock['stock_count'] = '1';
        $result = parent::add($stock);
        if (!$result['result']) {
            return $result;
        }

        // 履歴登録の為に既存在庫数を付加する
        $newStock = $result['data'];
        $newStock['stock_count_org'] = '0';

        // 在庫履歴（画面入力）を登録する
        $resultHist = $this->ModelStockHistories->addEntry($newStock, $asset['id']);
        if (!$resultHist['result']) {
            return $resultHist;
        }

        return $result;
    }

    /**
     * 在庫を登録する
     *  
     * - - -
     * 
     * @param array $planDetail 入庫予定詳細情報
     * @param array $instock 入庫情報
     * @param array $assets 資産情報（複数）
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($planDetail, $instock, $assets)
    {
        $assetType = $planDetail['asset_type'];

        $stock = [];
        $stock['domain_id'] = $planDetail['domain_id'];

        $result = parent::_invalid(['message' => '指定された入庫情報が正しくありません。', 'data' => ['planDetail' => $planDetail, 'assets' => $assets]]);
        $updates = [];

        foreach($assets as $asset) {
            $stock['asset_id']    = $asset['id'];
            $stock['stock_count'] = '1';
            $stock_count          = 0;
            if ($assetType == Configure::read('WNote.DB.Assets.AssetType.count')) {
                $existStock = $this->stock($asset['id']);
                if (count($existStock) > 0) {
                    $instock_count = is_numeric($instock['instock_count']) ? intVal($instock['instock_count']) : 0;
                    $stock_count   = ($existStock && is_numeric($existStock['stock_count'])) ? intVal($existStock['stock_count']) : 0;
                    $existStock['stock_count'] = $instock_count + $stock_count;
                    $result = parent::save($existStock->toArray());
                } else {
                    $result = parent::add($stock);
                }
            } else {
                $result = parent::add($stock);
            }
            if (!$result['result']) {
                return $result;
            }

            // 履歴登録の為に既存在庫数を付加する
            $newStock = $result['data'];
            $newStock['stock_count_org'] = $stock_count;
            $updates[] = $newStock;

            // 在庫履歴（入庫）を登録する
            $result = $this->ModelStockHistories->addInstock($newStock, $instock, $asset['id']);
            if (!$result['result']) {
                return $result;
            }
        }

        $result['data'] = $updates;
        return $result;
    }

    /**
     * 入庫時の在庫更新を行う
     *  
     * - - -
     * 
     * @param array $assetId 資産ID
     * @param array $instock 出庫情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateInstock($assetId, $instock)
    {
        $stock = $this->stock($assetId);
        if (!$stock || count($stock) == 0) {
            return parent::_invalid(['message' => '入庫対象の在庫情報がありません。', 'data' => ['method' => __METHOD__, 'assetId' => $assetId]]);
        }

        // 在庫数を更新
        $stock_count = is_numeric($stock['stock_count']) ? intVal($stock['stock_count']) : 0;
        $stock['stock_count'] = $stock_count + 1;
        if ($stock['stock_count'] > 1 && $instock['asset_type'] == Configure::read('WNote.DB.Assets.AssetType.asset')) {
            $stock['stock_count'] = 1; // 資産管理対象の場合は在庫を2以上にはしない
        }
        $updateStock = parent::save($stock->toArray());
        if (!$updateStock['result']) {
            return $updateStock;
        }

        // 在庫履歴（入庫）を登録する
        $newStock = $updateStock['data'];
        $newStock['stock_count_org'] = $stock_count;
        $updateHistory = $this->ModelStockHistories->addInstock($newStock, $instock, $assetId);
        if (!$updateHistory['result']) {
            return $updateHistory;
        }

        return $updateStock;
    }

    /**
     * 出庫時の在庫更新を行う
     *  
     * - - -
     * 
     * @param array $picking 出庫情報
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updatePicking($picking, $asset)
    {
        $assetType = $asset['asset_type'];
        $stock     = $this->stock($asset['id']);
        if (!$stock || count($stock) == 0) {
            return parent::_invalid(['message' => '出庫対象の在庫情報がありません。', 'data' => ['method' => __METHOD__, 'asset' => $asset]]);
        }

        $stock_count = is_numeric($stock['stock_count']) ? intVal($stock['stock_count']) : 0;
        if ($stock_count < 1) {
            return parent::_invalid(['message' => '出庫対象の在庫がありません。', 'data' => ['method' => __METHOD__, 'stock' => $stock]]);
        }

        // 在庫数を更新
        $stock['stock_count'] = $stock_count - 1;
        $updateStock = parent::save($stock->toArray());
        if (!$updateStock['result']) {
            return $updateStock;
        }

        // 在庫履歴（出庫）を登録する
        $newStock = $updateStock['data'];
        $newStock['stock_count_org'] = $stock_count;
        $updateHistory = $this->ModelStockHistories->addPicking($newStock, $picking, $asset);
        if (!$updateHistory['result']) {
            return $updateHistory;
        }

        return $updateStock;
    }

    /**
     * 棚卸時の在庫更新を行う
     *  
     * - - -
     * 
     * @param array $stocktakeDetail 棚卸明細情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateStocktake($stocktakeDetail)
    {
        $stock = $this->stock($stocktakeDetail['asset_id']);
        if (!$stock || count($stock) == 0) {
            return parent::_invalid(['message' => '棚卸差分更新対象の在庫情報がありません。', 'data' => ['method' => __METHOD__, 'detail' => $stocktakeDetail]]);
        }

        $stock_count = is_numeric($stock['stock_count']) ? intVal($stock['stock_count']) : 0;
        $stock['stock_count'] = (is_numeric($stocktakeDetail['stocktake_count'])) ? $stocktakeDetail['stocktake_count'] : 0;
        $updateStock = parent::save($stock->toArray());
        if (!$updateStock['result']) {
            return $updateStock;
        }

        // 在庫履歴（棚卸）を登録する
        $newStock = $updateStock['data'];
        $newStock['stock_count_org'] = $stock_count;
        $updateHistory = $this->ModelStockHistories->addStocktake($newStock, $stocktakeDetail);
        if (!$updateHistory['result']) {
            return $updateHistory;
        }

        return $updateStock;
    }
}
