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
 * 棚卸（Stocktakes）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelStocktakesComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['ModelStocktakeDetails'];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'Stocktakes';
        parent::initialize($config);
    }

    /**
     * 棚卸情報を取得する
     *  
     * - - -
     * 
     * @param string $stocktakeId 棚卸ID
     * @return \App\Model\Entity\Stocktake 棚卸情報
     */
    public function stocktake($stocktakeId)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'Stocktakes.id' => $stocktakeId
            ])
            ->contain([
                'StocktakeSuserName' => function($q) {
                    return $q->select(['id', 'kname']);
                }
            ])
            ->contain([
                'StocktakeConfirmSuserName' => function($q) {
                    return $q->select(['id', 'kname']);
                }
            ])
            ->contain([
                'StocktakeStsName' => function($q) {
                    return $q->select(['id', 'nkey', 'nid', 'name']);
                }
            ]);

        return $query->first();
    }

    /**
     * 未完了の棚卸情報のIDと棚卸日を取得する
     *  
     * - - -
     * 
     * @return array 未完了の棚卸情報のIDと棚卸日
     */
    public function incompleteList()
    {
        return $this->modelTable->find('valid')
            ->select(['id', 'stocktake_date'])
            ->where([
                'stocktake_sts <>' => Configure::read('WNote.DB.Stocktake.StocktakeSts.complete')
            ])
            ->all();
    }

    /**
     * 未完了、且つ、在庫締め済みの棚卸情報のIDと棚卸日を取得する
     *
     * - - -
     *
     * @return array 未完了の棚卸情報のIDと棚卸日
     */
    public function deadStockList()
    {
        return $this->modelTable->find('valid')
            ->select(['id', 'stocktake_date'])
            ->where([
                'stocktake_sts <>' => Configure::read('WNote.DB.Stocktake.StocktakeSts.complete'),
                'stock_deadline_date IS NOT' => null
            ])
            ->all();
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
            ->contain(['StocktakeStsName' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['StocktakeSuserName' => function($q) {
                return $q->select(['id', 'kname']);
            }])
            ->contain(['StocktakeConfirmSuserName' => function($q) {
                return $q->select(['id', 'kname']);
            }])
            ->order([
                'stocktake_date' => 'DESC',
                'start_date'     => 'DESC',
                'Stocktakes.id'  => 'DESC'
            ]);

        if (array_key_exists('stocktake_date_from', $cond) && $cond['stocktake_date_from'] !== '') {
            $query->where(['stocktake_date >=' => $cond['stocktake_date_from']]);
        }
        if (array_key_exists('stocktake_date_to', $cond) && $cond['stocktake_date_to'] !== '') {
            $query->where(['stocktake_date <=' => $cond['stocktake_date_to']]);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 在庫締め日を更新する
     *
     * - - -
     *
     * @param string $stocktakeId 棚卸ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateStockDeadline($stocktakeId)
    {
        $stocktake = parent::get($stocktakeId);
        if (!$stocktake || count($stocktake) == 0) {
            return $this->_invalid('棚卸情報が存在しない為、在庫締め日を更新できませんでした。'. ['method' => __METHOD__, 'stocktake_id' => $stocktakeId]);
        }

        $stocktake['stock_deadline_date'] = $this->today();

        return $this->save($stocktake->toArray());
    }

    /**
     * 棚卸を確定する
     *
     * - - -
     *
     * @param string $stocktakeId 棚卸ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function fix($stocktakeId)
    {
        $stocktake = parent::get($stocktakeId);
        if (!$stocktake || count($stocktake) == 0) {
            return $this->_invalid('棚卸情報が存在しない為、在庫締め日を更新できませんでした。'. ['method' => __METHOD__, 'stocktake_id' => $stocktakeId]);
        }

        $term = $this->ModelStocktakeDetails->workTerm($stocktakeId);

        $stocktake['stocktake_sts'] = Configure::read('WNote.DB.Stocktake.StocktakeSts.complete');
        $stocktake['start_date']    = $term['start_date'];
        $stocktake['end_date']      = $term['end_date'];

        return $this->save($stocktake->toArray());
    }
}
