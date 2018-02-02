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
 * 棚卸明細（StocktakeDetails）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelStocktakeDetailsComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['ModelStocktakeTargets'];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'StocktakeDetails';
        parent::initialize($config);
    }

    /**
     * サマリ情報(総棚卸数 - 資産管理資産)を取得する
     *  
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @return 総棚卸数
     */
    public function sumAssetStockDetails($stocktakeId)
    {
        return $this->_sumStockDetails($stocktakeId, Configure::read('WNote.DB.Assets.AssetType.asset'));
    }

    /**
     * サマリ情報(総棚卸数 - 数量管理資産)を取得する
     *  
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @return 総棚卸数
     */
    public function sumCountStockDetails($stocktakeId)
    {
        return $this->_sumStockDetails($stocktakeId, Configure::read('WNote.DB.Assets.AssetType.count'));
    }

    /**
     * (プライベート)サマリ情報(総棚卸数)を取得する
     *  
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @param string $assetType 資産タイプ
     * @return 総棚卸数
     */
    private function _sumStockDetails($stocktakeId, $assetType)
    {
        $query = $this->modelTable->find('validAll');
        $summary = $query
            ->where([
                'stocktake_id' => $stocktakeId,
                'asset_type'   => $assetType
            ])
            ->group(['stocktake_id'])
            ->select([
                'total' => $query->func()->sum('stocktake_count')
            ])
            ->first();

        return ($summary && count($summary) > 0) ? $summary['total'] : '0';
    }

    /**
     * サマリ情報(未対応棚卸件数 - 在庫未存在)を取得する
     *  
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @return 未対応棚卸件数
     */
    public function sumNoStocks($stocktakeId)
    {
        $query = $this->modelTable->find('validAll');
        $summary = $query
            ->where([
                'stocktake_id'  => $stocktakeId,
                'stocktake_kbn' => Configure::read('WNote.DB.Stocktake.StocktakeKbn.incomplete'),
                'asset_id IS'   => null
            ])
            ->group(['stocktake_id'])
            ->select([
                'count' => $query->func()->count('id')
            ])
            ->first();

        return ($summary && count($summary) > 0) ? $summary['count'] : '0';
    }

    /**
     * サマリ情報(未対応棚卸件数 - 在庫不足)を取得する
     *  
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @return 未対応棚卸件数
     */
    public function sumShortStocks($stocktakeId)
    {
        return $this->_sumUnmatchCountStocks($stocktakeId, Configure::read('WNote.DB.Stocktake.StUnmatchKbn.nostock'));
    }

    /**
     * サマリ情報(未対応棚卸件数 - 在庫過剰)を取得する
     *  
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @return 未対応棚卸件数
     */
    public function sumOverStocks($stocktakeId)
    {
        return $this->_sumUnmatchCountStocks($stocktakeId, Configure::read('WNote.DB.Stocktake.StUnmatchKbn.noitem'));
    }

    /**
     * (プライベート)サマリ情報(未対応棚卸件数 - 数量アンマッチ件数)を取得する
     *  
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @param string $unmatchKbn 差分区分
     * @return 未対応棚卸件数
     */
    private function _sumUnmatchCountStocks($stocktakeId, $unmatchKbn)
    {
        $query = $this->modelTable->find('validAll');
        $summary = $query
            ->where([
                'stocktake_id'    => $stocktakeId,
                'stocktake_kbn'   => Configure::read('WNote.DB.Stocktake.StocktakeKbn.incomplete'),
                'unmatch_kbn'     => $unmatchKbn,
                'asset_id IS NOT' => NULL
            ])
            ->group(['stocktake_id'])
            ->select([
                'count' => $query->func()->count('id')
            ])
            ->first();

        return ($summary && count($summary) > 0) ? $summary['count'] : '0';
    }

    /**
     * 指定棚卸IDに対する棚卸明細取得クエリを作成する
     *  
     * - - -
     * 
     * @param string $stocktakeId 棚卸ID
     * @return \Cake\ORM\Query クエリ
     */
    public function stocktakeDetailsQuery($stocktakeId)
    {
        return $this->modelTable->find('validAll')
            ->select([
                'id',
                'asset_id'
            ])
            ->andWhere([
                'stocktake_id' => $stocktakeId
            ]);
    }

    /**
     * 指定された棚卸IDの実施期間を取得する
     *  
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @return array 実施期間([start_date => <開始日>, end_date =><終了日>])
     */
    public function workTerm($stocktakeId)
    {
        $query = $this->modelTable->find('validAll');
        $term  = $query
            ->where([
                'stocktake_id'  => $stocktakeId
            ])
            ->group(['stocktake_id'])
            ->select([
                'start_date' => $query->func()->min('work_date'),
                'end_date'   => $query->func()->max('work_date')
            ])
            ->first();

        return $term;
    }

    /**
     * 資産ID、シリアル番号、資産管理番号のいずれかで重複する棚卸明細を取得する
     *
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @param string $assetId  資産ID
     * @param string $serialNo シリアル番号
     * @param string $assetNo  資産管理番号
     * @return \App\Model\Entity\StocktakeDetails|null 棚卸明細
     */
    public function duplication($stocktakeId, $assetId, $serialNo, $assetNo)
    {
        $detail = $this->modelTable->find('valid')
            ->andWhere([
                'stocktake_id' => $stocktakeId
            ])
            ->andWhere([
                'OR' => [
                    ['asset_id' => $assetId],
                    ['serial_no' => $serialNo],
                    ['asset_no' => $assetNo]
                ]
            ])
            ->first();

        return ($detail && count($detail) > 0) ? $detail : null;
    }

    /**
     * 棚卸差分の一覧を取得する（在庫が存在しない差分）
     *
     * - - -
     * @param string $stocktakeId  資産ID
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 棚卸差分（在庫未存在）の一覧（ResultSet or Array）
     */
    public function nostocks($stocktakeId, $toArray = false)
    {
        $query = $this->modelTable->find('validAll')
            ->where([
                'StocktakeDetails.stocktake_id' => $stocktakeId,
                'StocktakeDetails.asset_id IS'  => null
             ])
            ->contain(['StocktakeDetailAssetTypeName' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['StocktakeKbnName' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->contain(['StocktakeUnmatchKbnName' => function($q) {
                return $q->select(['nkey', 'nid', 'name']);
            }])
            ->order([
                'StocktakeDetails.serial_no',
                'StocktakeDetails.asset_no'
            ]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 棚卸明細（資産棚卸）を登録する
     *
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @param array $entries 棚卸入力情報（複数件）
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function entryAssets($stocktakeId, $entries)
    {
        foreach($entries as $entry) {
            $detail = $this->duplication($stocktakeId, $entry['id'], $entry['serial_no'], $entry['asset_no']);
            $detail = ($detail) ? $detail->toArray() : [];
            $detail = $this->_makeBaseEntryAssets($detail, $stocktakeId, $entry);

            if (isset($entry['id']) && $entry['id'] != '') {
                $detail['asset_id'] = $entry['id'];
            } else {
                $detail['stocktake_kbn'] = Configure::read('WNote.DB.Stocktake.StocktakeKbn.incomplete');
                $detail['unmatch_kbn'] = Configure::read('WNote.DB.Stocktake.StUnmatchKbn.nostock');
            }

            $result = ($detail['id']) ? parent::save($detail) : parent::add($detail);
            if (!$result['result']) {
                return $result;
            }
        }

        return $this->_result(true, $stocktakeId);
    }

    /**
     * (プライベート)棚卸明細（資産棚卸）登録用データを作成する
     *
     * - - -
     * @param array $detail 登録データ配列
     * @param string $stocktakeId 棚卸ID
     * @param array $entry 棚卸入力情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    private function _makeBaseEntryAssets($detail, $stocktakeId, $entry) {
        $detail['domain_id']       = $this->current();
        $detail['stocktake_id']    = $stocktakeId;
        $detail['work_date']       = $this->today();
        $detail['work_suser_id']   = $this->user();
        $detail['asset_type']      = Configure::read('WNote.DB.Assets.AssetType.asset');
        $detail['serial_no']       = $entry['serial_no'];
        $detail['asset_no']        = $entry['asset_no'];
        $detail['stocktake_count'] = 1;
        $detail['stocktake_kbn']   = Configure::read('WNote.DB.Stocktake.StocktakeKbn.match');
        $detail['unmatch_kbn']     = Configure::read('WNote.DB.Stocktake.StUnmatchKbn.match');

        return $detail;
    }

    /**
     * 棚卸明細（数量棚卸）を登録する
     *
     * - - -
     * @param array $entry 棚卸入力情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function entryCount($entry)
    {
        $stocktakeId = $entry['stocktake_id'];

        $detail = [];
        $detail['domain_id']         = $this->current();
        $detail['stocktake_id']      = $stocktakeId;
        $detail['work_date']         = $this->today();
        $detail['work_suser_id']     = $this->user();
        $detail['asset_id']          = $entry['asset_id'];
        $detail['asset_type']        = Configure::read('WNote.DB.Assets.AssetType.count');
        $detail['classification_id'] = $entry['classification_id'];
        $detail['product_id']        = $entry['product_id'];
        $detail['product_model_id']  = $entry['product_model_id'];
        $detail['stocktake_count']   = $entry['stocktake_count'];

        $match = $this->_matchCount($entry);
        if ($match !== 0 && !$match) {
            return $this->_invalid('指定された棚卸明細に対する在庫情報がありません。', ['method' => __METHOD__, 'stocktake_id' => $stocktakeId, 'entry' => $entry]);
        }
        $detail = $this->_setMatchData($match, $detail);

        return parent::add($detail);
    }

    /**
     * 棚卸明細（数量棚卸）を保存する
     *
     * - - -
     * @param array $entry 棚卸入力情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function saveCount($entry)
    {
        $id = $entry['stocktake_detail_id'];

        $detail = parent::get($id);
        if (!$detail || count($detail) == 0) {
            return $this->_invalid('指定された棚卸明細情報がありません。', ['method' => __METHOD__, 'id' => $id, 'entry' => $entry]);
        }

        $detail['work_date']       = $this->today();
        $detail['work_suser_id']   = $this->user();
        $detail['stocktake_count'] = $entry['stocktake_count'];

        $match = $this->_matchCount($detail['stocktake_id'], $entry);
        if ($match !== 0 && !$match) {
            return $this->_invalid('指定された棚卸明細に対する在庫情報がありません。', ['method' => __METHOD__, 'id' => $id, 'entry' => $entry]);
        }
        $detail = $this->_setMatchData($match, $detail);

        return parent::save($detail->toArray());
    }

    /**
     * (private)数量棚卸の在庫数量と棚卸数量のマッチングを行う
     * 
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @param array $entry 棚卸入力情報
     * @return boolean|integer false: 在庫情報なし ／ 数値：在庫数量 - 棚卸数量の差異
     */
    private function _matchCount($stocktakeId, $entry)
    {
        $stock = $this->ModelStocktakeTargets->findByAssetId($stocktakeId, $entry['asset_id']);
        if (!stock || count($stock) == 0) {
            return false;
        }

        $sc = intVal($stock['stock_count']);
        $tc = is_numeric($entry['stocktake_count']) ? intVal($entry['stocktake_count']) : 0;

        return $sc - $tc;
    }

    /**
     * (private)数量棚卸の対応状況、差分区分の設定を行う
     * 
     * - - -
     * @param integer $match マッチング差異の数量
     * @param array $detail 棚卸明細情報
     * @return array 棚卸明細情報
     */
    private function _setMatchData($match, $detail)
    {
        if($match == 0) { // 一致
            $detail['stocktake_kbn'] = Configure::read('WNote.DB.Stocktake.StocktakeKbn.match');
            $detail['unmatch_kbn']   = Configure::read('WNote.DB.Stocktake.StUnmatchKbn.match');
        } else if($match < 0) { // 在庫不足
            $detail['stocktake_kbn'] = Configure::read('WNote.DB.Stocktake.StocktakeKbn.incomplete');
            $detail['unmatch_kbn']   = Configure::read('WNote.DB.Stocktake.StUnmatchKbn.nostock');
        } else { // 棚卸不足
            $detail['stocktake_kbn'] = Configure::read('WNote.DB.Stocktake.StocktakeKbn.incomplete');
            $detail['unmatch_kbn']   = Configure::read('WNote.DB.Stocktake.StUnmatchKbn.noitem');
        }

        return $detail;
    }

    /**
     * 棚卸差分（数量差分）を対応済に更新する
     *
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @param array $unmatch 差分データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateUnmatch($stocktakeId, $unmatch)
    {
        $id = $unmatch['stocktake_detail_id'];

        $detail = parent::get($id);
        if (!$detail || count($detail) == 0) {
            $detail = $this->_makeBaseEntryAssets([], $stocktakeId, $unmatch);
            $detail['stocktake_count'] = 0;
            $detail['asset_id']    = $unmatch['asset_id'];
            $detail['unmatch_kbn'] = Configure::read('WNote.DB.Stocktake.StUnmatchKbn.noitem');
        }

        $detail['stocktake_kbn'] = Configure::read('WNote.DB.Stocktake.StocktakeKbn.complete');
        $detail['correspond']    = '棚卸数量にて在庫数量を更新';

        return ($detail['id']) ? parent::save($detail->toArray()) : parent::add($detail);
    }

    /**
     * 棚卸差分（在庫なし）を対応済に更新する
     *
     * - - -
     * @param string $stocktakeId 棚卸ID
     * @param string $correspond 対応内容
     * @param array $unmatch 差分データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateNostock($stocktakeId, $correspond, $unmatch)
    {
        $id = $unmatch['stocktake_detail_id'];

        $detail = parent::get($id);
        if (!$detail || count($detail) == 0) {
            return $this->_invalid('指定された棚卸明細情報がありません。', ['method' => __METHOD__, 'id' => $id, 'unmatch' => $unmatch]);
        }

        $detail['stocktake_kbn'] = Configure::read('WNote.DB.Stocktake.StocktakeKbn.complete');
        $detail['correspond']    = $correspond;

        return parent::save($detail->toArray());
    }
}
