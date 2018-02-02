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
 * 棚卸対象（StocktakeTargets）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelStocktakeTargetsComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['ModelStocks', 'ModelStocktakeDetails'];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'StocktakeTargets';
        parent::initialize($config);
    }

    /**
     * サマリ情報(総在庫数 - 資産管理資産)を取得する
     *  
     * - - -
     * 
     * @param string $stocktakeId 棚卸ID
     * @return 総在庫数
     */
    public function sumAssetStockTargets($stocktakeId)
    {
        return $this->_sumStockTargets($stocktakeId, Configure::read('WNote.DB.Assets.AssetType.asset'));
    }

    /**
     * サマリ情報(総在庫数 - 数量管理資産)を取得する
     *  
     * - - -
     * 
     * @param string $stocktakeId 棚卸ID
     * @return 総在庫数
     */
    public function sumCountStockTargets($stocktakeId)
    {
        return $this->_sumStockTargets($stocktakeId, Configure::read('WNote.DB.Assets.AssetType.count'));
    }

    /**
     * (プライベート)サマリ情報(総在庫数)を取得する
     *  
     * - - -
     * 
     * @param string $stocktakeId 棚卸ID
     * @param string $assetType 資産タイプ
     * @return 総在庫数
     */
    private function _sumStockTargets($stocktakeId, $assetType)
    {
        $query = $this->modelTable->find('valid');
        $summary = $query
            ->where([
                'stocktake_id' => $stocktakeId,
                'asset_type'   => $assetType
            ])
            ->group(['stocktake_id'])
            ->select([
                'total' => $query->func()->sum('stock_count')
            ])
            ->first();

        return ($summary && count($summary) > 0) ? $summary['total'] : '0';
    }

    /**
     * サマリ情報(未対応在庫件数 - 在庫過剰／棚卸未存在)を取得する
     *  
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @return 未対応在庫件数
     */
    public function sumOverStockTargets($stocktakeId)
    {
        $targets = $this->ModelStocktakeDetails->stocktakeDetailsQuery($stocktakeId);
        $targets->andWhere(function ($exp, $q) {
            return $exp->equalFields('StocktakeTargets.asset_id', 'StocktakeDetails.asset_id');
        });

        $query = $this->modelTable->find('valid');
        $summary = $query
            ->where([
                'stocktake_id' => $stocktakeId
            ])
            ->andWhere(function ($exp, $q) use ($targets) {
                return $exp->notExists($targets);
            })
            ->group(['stocktake_id'])
            ->select([
                'count' => $query->func()->count('id')
            ])
            ->first();

        return ($summary && count($summary) > 0) ? $summary['count'] : '0';
    }

    /**
     * 指定された資産IDより在庫を取得する
     *
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @param string $assetId 資産ID
     * @return \App\Model\Entity\StocktakeTarget 在庫情報
     */
    public function findByAssetId($stocktakeId, $assetId)
    {
        return $this->modelTable->find('valid')
            ->where([
                'stocktake_id' => $stocktakeId,
                'asset_id'     => $assetId
            ])
            ->first();
    }

    /**
     * 数量管理在庫資産を検索する
     *
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @param array $cond 検索条件
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 当日の出庫予定一覧（ResultSet or Array）
     */
    public function stockCountAssets($stocktakeId, $cond, $toArray = false)
    {
        $query = $this->modelTable->find('validAll')
            ->contain(['StocktakeDetails' => function($q) {
                    return $q->select(['id', 'asset_id', 'stocktake_count']);
            }])
            ->contain(['Assets' => function($q) {
                    return $q->select(['id', 'classification_id', 'maker_id', 'product_id', 'product_model_id']);
            }])
            ->contain(['Assets.Classifications' => function($q) {
                    return $q->select(['id', 'category_id', 'kname']);
            }])
            ->contain(['Assets.Classifications.Categories' => function($q) {
                    return $q->select(['id', 'kname']);
            }])
            ->contain(['Assets.Companies' => function($q) {
                    return $q->select(['id', 'kname']);
            }])
            ->contain(['Assets.Products' => function($q) {
                    return $q->select(['id', 'kname']);
            }])
            ->contain(['Assets.ProductModels' => function($q) {
                    return $q->select(['id', 'kname']);
            }])
            ->andWhere([
                'StocktakeTargets.stocktake_id' => $stocktakeId,
                'StocktakeTargets.asset_type'   => Configure::read('WNote.DB.Assets.AssetType.count')
            ])
            ->order([
                'Classifications.category_id' => 'ASC',
                'Assets.classification_id'    => 'ASC',
                'Assets.maker_id'             => 'ASC',
                'Assets.product_id'           => 'ASC',
                'Assets.product_model_id'     => 'ASC'
            ]);

        // 検索条件を追加
        $hasCondition = false;
        if ($this->hasSearchParams('classification_id', $cond)) {
            $query->andWhere(['Assets.classification_id' => $cond['classification_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('product_id', $cond)) {
            $query->andWhere(['Assets.product_id' => $cond['product_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('product_model_id', $cond)) {
            $query->andWhere(['Assets.product_model_id' => $cond['product_model_id']]);
            $hasCondition = true;
        }

        if (!$hasCondition) {
            $query->andWhere(['1=0']); // 検索結果を取得しない
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 棚卸情報を検索する
     *  
     * - - -
     * @param array $cond 検索条件
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 棚卸一覧（ResultSet or Array）
     */
    public function search($cond, $toArray = false)
    {
        $query = $this->modelTable->find('validAll')
            ->contain(['StocktakeTargetAssetTypeName' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['Stocktakes.StocktakeStsName' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['Stocktakes.StocktakeSuserName' => function($q) {
                return $q->select(['id', 'kname']);
            }])
            ->contain(['Stocktakes.StocktakeConfirmSuserName' => function($q) {
                return $q->select(['id', 'kname']);
            }])
            ->contain(['StocktakeDetails'])
            ->contain(['StocktakeDetails.StocktakeDetailAssetTypeName' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['StocktakeDetails.StocktakeKbnName' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['StocktakeDetails.StocktakeUnmatchKbnName' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['Assets' => function($q) {
                return $q->select(['id', 'classification_id', 'product_id', 'product_model_id', 'serial_no', 'asset_no']);
            }])
            ->contain(['Assets.Classifications' => function($q) {
                return $q->select(['id', 'kname']);
            }])
            ->contain(['Assets.Products' => function($q) {
                return $q->select(['id', 'kname']);
            }])
            ->contain(['Assets.ProductModels' => function($q) {
                return $q->select(['id', 'kname']);
            }])

            ->order([
                'Stocktakes.stocktake_date'          => 'DESC',
                'Stocktakes.start_date'              => 'DESC',
                'Assets.classification_id'           => 'ASC',
                'Assets.product_id'                  => 'ASC',
                'Assets.product_model_id'            => 'ASC',
                'StocktakeDetails.stocktake_kbn'     => 'ASC',
                'StocktakeDetails.unmatch_kbn'       => 'ASC'
            ]);

        if (array_key_exists('stocktake_date_from', $cond) && $cond['stocktake_date_from'] !== '') {
            $query->where(['Stocktakes.stocktake_date >=' => $cond['stocktake_date_from']]);
        }
        if (array_key_exists('stocktake_date_to', $cond) && $cond['stocktake_date_to'] !== '') {
            $query->where(['Stocktakes.stocktake_date <=' => $cond['stocktake_date_to']]);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 棚卸数量差分の一覧を取得する（在庫が存在しない差分を除く）
     *
     * - - -
     * @param string $stocktakeId  資産ID
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 棚卸数量差分の一覧（ResultSet or Array）
     */
    public function countUnmatches($stocktakeId, $toArray = false)
    {
        $query = $this->modelTable->find('validAll')
            ->where([
                'StocktakeTargets.stocktake_id' => $stocktakeId
             ])
            ->where(['OR' => [
                ['StocktakeDetails.stocktake_kbn' => Configure::read('WNote.DB.Stocktake.StocktakeKbn.incomplete')],
                ['AND' => [['StocktakeDetails.stocktake_kbn IS' => null], ['StocktakeDetails.id IS' => null]]]
            ]])
            ->where(['OR' => [
                ['StocktakeDetails.unmatch_kbn <>' => Configure::read('WNote.DB.Stocktake.StUnmatchKbn.match')],
                ['AND' => [['StocktakeDetails.unmatch_kbn IS' => null], ['StocktakeDetails.id IS' => null]]]
            ]])
            ->contain([
                'Assets' => function($q) {
                    return $q->select(['id', 'classification_id', 'product_id', 'product_model_id', 'serial_no', 'asset_no']);
                }
            ])
            ->contain([
                'Assets.Classifications' => function($q) {
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
                'StocktakeDetails' => function($q) {
                    return $q->where(['StocktakeDetails.asset_id IS NOT' => null]);
                }
            ])
            ->contain([
                'StocktakeDetails.StocktakeUnmatchKbnName' => function($q) {
                    return $q->select(['nkey', 'nid', 'name']);
                }
            ])
            ->contain([
                'Stocks' => function($q) {
                    return $q->select(['id', 'asset_id', 'stock_count']);
                }
            ])
            ->order([
                'Assets.serial_no',
                'StocktakeDetails.serial_no',
                'Assets.asset_no',
                'StocktakeDetails.asset_no'
            ])
            ->limit(Configure::read('WNote.DB.ListLimit.maxcount'));

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 現在時点の在庫より棚卸対象在庫を作成する
     *
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function create($stocktakeId)
    {
        // 棚卸対象在庫を削除する
        $delete = parent::deleteAll([
            'domain_id'    => $this->current(),
            'stocktake_id' => $stocktakeId
        ]);
        if (!$delete['result']) {
            return $delete;
        }

        // 現在在庫を対象としたSELECT用のクエリを生成
        $select = $this->ModelStocks->stocktakeTargetsQuery($stocktakeId);

        // 現在在庫を登録
        $result = $this->modelTable->query()
            ->insert(['domain_id', 'stocktake_id', 'asset_id', 'asset_type', 'stock_count', 'dsts', 'created_at', 'created_user', 'modified_at', 'modified_user'])
            ->values($select)
            ->execute();

        if (!$result || $result->errorCode() != '00000') {
            return $this->_result(false, false, ['error_message' => 'failed to insert to stocktake from stocks.']);
        }

        return $this->_result(true, $result, false);
    }

}
