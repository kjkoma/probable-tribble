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
use Cake\I18n\Time;
use Cake\ORM\Query;

/**
 * 資産（Assets）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelAssetsComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['ModelAssetUsers', 'ModelCompanies', 'ModelProducts', 'ModelProductModels'];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'Assets';
        parent::initialize($config);
    }

    /**
     * 現在のPCの総資産件数を取得する
     *  
     * - - -
     * 
     * @return integer 資産件数（PC）
     */
    public function countPc()
    {
        $query = $this->modelTable->find('validAll')
            ->select(['id', 'classification_id', 'asset_type', 'asset_sts'])
            ->andWhere(['Assets.asset_type' => Configure::read('WNote.DB.Assets.AssetType.asset')])
            ->andWhere(['Assets.asset_sts NOT IN' => [Configure::read('WNote.DB.Assets.AssetSts.abrogate'), Configure::read('WNote.DB.Assets.AssetSts.lost')]])
            ->matching('Classifications.Categories', function($q) {
                return $q->where(['Categories.id IN' => Configure::read('WNote.DB.Categories.pc')]);
            });

        return $query->count();
    }

    /**
     * 資産情報を取得する
     *  
     * - - -
     * 
     * @param string $assetId 資産ID
     * @return \App\Model\Entity\Asset 資産情報
     */
    public function asset($assetId)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'Assets.id' => $assetId
            ]);
        $query = $this->_addAssociation($query);
        $query->contain(['AssetUsers']);

        return $query->first();
    }

    /**
     * 資産情報を検索する
     *  
     * - - -
     * 
     * @param array $cond 検索条件
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 資産一覧（ResultSet or Array）
     */
    public function search($cond, $toArray = false)
    {
        $query = $this->_makeSearchQuery($cond);
        $query = $query->andwhere(['Assets.asset_sts <>' => 
            Configure::read('WNote.DB.Assets.AssetSts.abrogate')
        ]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 資産情報の内、在庫分を検索する
     *  
     * - - -
     * 
     * @param array $cond 検索条件
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 在庫一覧（ResultSet or Array）
     */
    public function searchStock($cond, $toArray = false)
    {
        $query = $this->_makeSearchQuery($cond);
        $query = $query->andwhere(['Assets.asset_sts IN' => [
            Configure::read('WNote.DB.Assets.AssetSts.new'),
            Configure::read('WNote.DB.Assets.AssetSts.stock'),
            Configure::read('WNote.DB.Assets.AssetSts.repair'),
            Configure::read('WNote.DB.Assets.AssetSts.abrogate_plan')
        ]]);
        $query = $query->andwhere(['Stocks.stock_count > ' => 0]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 資産情報を検索する
     *  
     * - - -
     * 
     * @param array $criteria 検索条件(シリアル or 資産管理 or PC名)
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 資産一覧（ResultSet or Array）
     */
    public function searchCriteria($criteria, $toArray = false)
    {
        $query = $this->_makeSearchQuery([], true);
        $query->andWhere([
            'OR' => [
                ['Assets.serial_no LIKE'  => '%' . $criteria . '%'],
                ['Assets.asset_no LIKE'   => '%' . $criteria . '%'],
                ['Assets.kname LIKE' => '%' . $criteria . '%'],
            ]
        ])
        ->limit(Configure::read('WNote.DB.ListLimit.maxcount'));

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * (Private)資産情報を検索するクエリを作成する
     *  
     * - - -
     * 
     * @param array $cond 検索条件
     * @param boolean $isSearchAll 未指定時に検索するかどうか(デフォルト：false)
     * @return \Cake\ORM\Query クエリビルダ
     */
    private function _makeSearchQuery($cond, $isSearchAll = false)
    {
        $query = $this->modelTable->find('validAll')
            ->order([
                'Assets.asset_type'      => 'ASC',
                'Categories.kname'       => 'ASC',
                'Classifications.kname'  => 'ASC',
                'Assets.maker_id'        => 'ASC',
                'Assets.product_id'      => 'ASC',
                'Assets.asset_sts'       => 'ASC',
                'Assets.asset_sub_sts'   => 'ASC',
                'asset_no'               => 'ASC',
                'serial_no'              => 'ASC'
            ]);
        $query = $this->_addAssociation($query);

        // 検索条件を追加
        $hasCondition = false;
        if ($this->hasSearchParams('asset_type', $cond)) {
            $query->andWhere(['Assets.asset_type' => $cond['asset_type']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('asset_sts', $cond)) {
            $query->andWhere(['Assets.asset_sts' => $cond['asset_sts']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('asset_sub_sts', $cond)) {
            $query->andWhere(['Assets.asset_sub_sts' => $cond['asset_sub_sts']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('category_id', $cond)) {
            $query->andWhere(['Classifications.category_id' => $cond['category_id']]);
            $hasCondition = true;
        }
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
        if ($this->hasSearchParams('maker_id', $cond)) {
            $query->andWhere(['Assets.maker_id' => $cond['maker_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('serial_no', $cond)) {
            $query->andWhere(['Assets.serial_no LIKE' => '%' . $cond['serial_no'] . '%']);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('asset_no', $cond)) {
            $query->andWhere(['Assets.asset_no LIKE' => '%' . $cond['asset_no'] . '%']);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('first_instock_date_from', $cond)) {
            $query->andWhere(['Assets.first_instock_date >=' => $cond['first_instock_date_from']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('first_instock_date_to', $cond)) {
            $query->andWhere(['Assets.first_instock_date <=' => $cond['first_instock_date_to']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('account_date_from', $cond)) {
            $query->andWhere(['Assets.account_date >=' => $cond['account_date_from']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('account_date_to', $cond)) {
            $query->andWhere(['Assets.account_date <=' => $cond['account_date_to']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('abrogate_date_from', $cond)) {
            $query->andWhere(['Assets.abrogate_date >=' => $cond['abrogate_date_from']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('abrogate_date_to', $cond)) {
            $query->andWhere(['Assets.abrogate_date <=' => $cond['abrogate_date_to']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('support_limit_date_from', $cond)) {
            $query->andWhere(['Assets.support_limit_date >=' => $cond['support_limit_date_from']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('support_limit_date_to', $cond)) {
            $query->andWhere(['Assets.support_limit_date <=' => $cond['support_limit_date_to']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('current_organization_id', $cond)) {
            $query->andWhere(['Users.organization_id' => $cond['current_organization_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('current_user_id', $cond)) {
            $query->andWhere(['Assets.current_user_id' => $cond['current_user_id']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('remarks', $cond)) {
            $query->andWhere(['Assets.remarks LIKE' => '%' . $cond['remarks'] . '%']);
            $hasCondition = true;
        }

        if (!$hasCondition && !$isSearchAll) {
            $query->andWhere(['1=0']); // 検索結果を取得しない
        }

        return $query;
    }

    /**
     * 指定されたクエリビルダに標準的な関連モデルを追加する
     *  
     * - - -
     * @param Cake\ORM\Query クエリビルダ
     * @return Cake\ORM\Query クエリビルダ
     */
    private function _addAssociation(Query $query) {
        return $query
            ->contain(['AssetAttributes'])
            ->contain(['Classifications' => function($q) {
                return $q
                    ->select(['id', 'category_id', 'kname']);
            }])
            ->contain(['Classifications.Categories' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['Products' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['Companies' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['ProductModels' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['Products' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['Stocks' => function($q) {
                return $q
                    ->select(['id', 'asset_id', 'stock_count']);
            }])
            ->contain(['Users' => function($q) {
                return $q
                    ->select(['id', 'sname', 'fname']);
            }])
            ->contain(['Users.Organizations' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['AssetTypeName' => function($q) {
                return $q
                    ->select(['id', 'nkey', 'nid', 'name']);
            }])
            ->contain(['AssetStsName' => function($q) {
                return $q
                    ->select(['id', 'nkey', 'nid', 'name']);
            }])
            ->contain(['AssetSubStsName' => function($q) {
                return $q
                    ->select(['id', 'nkey', 'nid', 'name']);
            }])
            ->contain(['AssetAbrogateSuser' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['AssetCreatedSuser' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['AssetModifiedSuser' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }]);
    }

    /**
     * 廃棄予定の資産一覧を取得する
     *  
     * - - -
     * 
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 廃棄予定の資産一覧（ResultSet or Array）
     */
    public function abrogatePlans($toArray = false)
    {
        $query = $this->_makeAbrogateQuery();
        $query
            ->where([
                'Assets.asset_sts'  => Configure::read('WNote.DB.Assets.AssetSts.abrogate_plan')
            ]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 廃棄済の資産一覧を取得する
     *  
     * - - -
     * 
     * @param array $cond 検索条件
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 廃棄予定の資産一覧（ResultSet or Array）
     */
    public function abrogates($cond, $toArray = false)
    {
        $query = $this->_makeAbrogateQuery();
        $query
            ->where([
                'Assets.asset_sts'  => Configure::read('WNote.DB.Assets.AssetSts.abrogate')
            ]);

        $hasCondition = false;
        if ($this->hasSearchParams('abrogate_date_from', $cond)) {
            $query->andWhere(['Assets.abrogate_date >=' => $cond['abrogate_date_from']]);
            $hasCondition = true;
        }
        if ($this->hasSearchParams('abrogate_date_to', $cond)) {
            $query->andWhere(['Assets.abrogate_date <=' => $cond['abrogate_date_to']]);
            $hasCondition = true;
        }

        if (!$hasCondition) {
            $query->andWhere(['1=0']); // 検索結果を取得しない
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * (Private)廃棄予定 or 廃棄済の資産情報を検索するクエリを作成する
     *  
     * - - -
     * 
     * @return \Cake\ORM\Query クエリビルダ
     */
    private function _makeAbrogateQuery()
    {
        $query = $this->modelTable->find('validAll');
        $query
            ->contain(['Classifications' => function($q) {
                return $q
                    ->select(['id', 'category_id', 'kname']);
            }])
            ->contain(['Products' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['Companies' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['Products' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->contain(['Repairs' => function($q) {
                return $q
                    ->select([
                        'repair_asset_id',
                        'repair_count' => $q->func()->count('id')
                    ])
                    ->group(['repair_asset_id']);
            }])
            ->contain(['AssetAbrogateSuser' => function($q) {
                return $q
                    ->select(['id', 'kname']);
            }])
            ->where([
                'Assets.asset_type' => Configure::read('WNote.DB.Assets.AssetType.asset')
            ])
            ->order([
                'Classifications.kname'  => 'ASC',
                'Assets.maker_id'        => 'ASC',
                'Assets.product_id'      => 'ASC',
                'asset_no'               => 'ASC',
                'serial_no'              => 'ASC'
            ]);

        return $query;
    }

    /**
     * 指定された製品とモデルの数量管理の資産を取得する
     *  
     * - - -
     * 
     * @param string $productId 製品ID
     * @param string $modelId 製品モデルID
     * @return \App\Model\Entity\Asset 資産情報
     */
    public function assetCountType($productId, $modelId)
    {
        $findModelId = ($modelId === '') ? null : $modelId;

        $query = $this->modelTable->find('valid')
            ->where([
                'asset_type'       => Configure::read('WNote.DB.Assets.AssetType.count'),
                'product_id'       => $productId,
            ]);

        if (!is_null($findModelId) && $findModelId !== '') {
            $query->where([
                'product_model_id' => $findModelId
            ]);
        }

        return $query->first();
    }

    /**
     * シリアル番号より資産情報を取得する
     *  
     * - - -
     * 
     * @param string $serialNo シリアル番号
     * @param string $productId 製品ID
     * @param string $modelId 製品モデルID
     * @return \App\Model\Entity\Asset 資産情報
     */
    public function bySerialNo($serialNo, $productId = null, $modelId = null)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'serial_no' => $serialNo
            ]);

        if ($productId && $productId !== '') {
            $query->where([ 'product_id' => $productId ]);
        }
        if ($modelId && $modelId !== '') {
            $query->where([ 'product_model_id' => $modelId ]);
        }

        return $query->first();
    }

    /**
     * 資産管理番号より資産情報を取得する
     *  
     * - - -
     * 
     * @param string $assetNo 資産管理番号
     * @return \App\Model\Entity\Asset 資産情報
     */
    public function byAssetNo($assetNo)
    {
        return $this->modelTable->find('valid')
            ->where([
                'asset_no' => $assetNo
            ])
            ->first();
    }

    /**
     * シリアル番号か資産管理番号のいずれか値の設定されている番号より資産情報を取得する
     *  
     * - - -
     * 
     * @param string $serialNo シリアル番号
     * @param string $assetNo 資産管理番号
     * @param string $productId 製品ID
     * @param string $modelId 製品モデルID
     * @return \App\Model\Entity\Asset 資産情報
     */
    public function bySerialOrAssetNo($serialNo, $assetNo, $productId = null, $modelId = null)
    {
        $asset = null;
        if ($serialNo && $serialNo !== '') {
            $asset = $this->bySerialNo($serialNo, $productId, $modelId);
        } else {
            $asset = $this->byAssetNo($assetNo);
        }

        return ($asset && count($asset) > 0) ? $asset : null;
    }

    /**
     * デフォルトの資産名を取得する（メーカー名 + 製品名 ＋ モデル名）
     *  
     * - - -
     * 
     * @param string $productId 製品ID
     * @param string $modelId 製品モデルID
     * @return string デフォルト資産名称
     */
    public function defaultAssetName($productId, $modelId)
    {
        $name = '';

        // 製品名称取得
        $product = $this->ModelProducts->get($productId);
        $name = $product['kname'];

        // モデル名
        if (!is_null($modelId) && $modelId !== '') {
            $model = $this->ModelProductModels->get($modelId);
            $name  = $name . ' ' . $model['kname'];
        }

        // メーカー名
        $maker = $this->ModelCompanies->get($product['maker_id']);
        $name = $maker['kname'] . ' ' . $name;

        return $name;
    }

    /**
     * 画面入力より資産を登録する
     *  
     * - - -
     * 
     * @param array $entry 画面入力情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addEntry($entry)
    {
        // メーカーID取得
        $product = $this->ModelProducts->get($entry['product_id']);

        // 資産登録情報
        $asset = $entry;
        $asset['domain_id']          = $this->current();
        $asset['asset_type']         = $product['asset_type'];
        $asset['classification_id']  = $product['classification_id'];
        $asset['maker_id']           = $product['maker_id'];
        $asset['kname']              = (strlen(trim($entry['kname'])) === 0) ? $this->defaultAssetName($entry['product_id'], $entry['product_model_id']) : trim($entry['kname']);

        return parent::add($asset);
    }

    /**
     * 画面入力より資産を登録する
     *  
     * - - -
     * 
     * @param array $entry 画面入力情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function editEntry($entry)
    {
        // メーカーID取得
        $product = $this->ModelProducts->get($entry['product_id']);

        // 資産登録情報
        $asset = $entry;
        $asset['classification_id'] = $product['classification_id'];
        $asset['maker_id']          = $product['maker_id'];
        $asset['kname']             = (strlen(trim($entry['kname'])) === 0) ? $this->defaultAssetName($entry['product_id'], $entry['product_model_id']) : trim($entry['kname']);

        return parent::save($asset);
    }

    /**
     * 入庫情報より資産登録／更新を行う
     *  
     * - - -
     * 
     * @param array $assetId 資産ID(新規時は null or '')
     * @param array $instock 入庫情報
     * @param array $planDetail 入庫予定詳細情報
     * @param array $serials シリアル情報(新規時に追加するシリアル)
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function instock($assetId, $instock, $planDetail, $serials)
    {
        // 新規資産登録
        if (!$assetId || $assetId === '') {
            return $this->addNew($planDetail, $serials);
        }

        // 資産更新
        return $this->updateInstock($assetId, $instock);
    }

    /**
     * 新規入庫情報より資産を登録する
     *  
     * - - -
     * 
     * @param array $planDetail 入庫予定詳細情報
     * @param array $serials シリアル情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($planDetail, $serials)
    {
        // メーカーID取得
        $product = $this->ModelProducts->get($planDetail['product_id']);

        // 資産登録情報
        $asset = [];
        $asset['domain_id']          = $planDetail['domain_id'];
        $asset['asset_type']         = $planDetail['asset_type'];
        $asset['classification_id']  = $planDetail['classification_id'];
        $asset['maker_id']           = $product['maker_id'];
        $asset['product_id']         = $planDetail['product_id'];
        $asset['product_model_id']   = $planDetail['product_model_id'];
        $asset['kname']              = $this->defaultAssetName($asset['product_id'], $asset['product_model_id']);
        $asset['asset_sts']          = Configure::read('WNote.DB.Assets.AssetSts.new');
        $asset['asset_sub_sts']      = Configure::read('WNote.DB.Assets.AssetSubSts.other');
        $asset['first_instock_date'] = Time::now()->i18nFormat('yyyy/MM/dd');
        $asset['support_limit_date'] = $planDetail['support_limit_date'];
        $asset['remarks']            = $planDetail['remarks'];

        $result = parent::_invalid(['message' => '指定された入庫情報が正しくありません。', 'data' => ['planDetail' => $planDetail, 'serials' => $serials]]);
        $updates = [];

        // 資産管理の場合
        if ($planDetail['asset_type'] == Configure::read('WNote.DB.Assets.AssetType.asset')) {
            foreach($serials as $serial) {
                $asset['serial_no'] = $serial;
                $result = parent::add($asset);
                if (!$result['result']) {
                    return $result;
                }
                $updates[] = $result['data'];
            }
        }

        // 数量管理の場合
        if ($planDetail['asset_type'] == Configure::read('WNote.DB.Assets.AssetType.count')) {
            $existsAsset = $this->assetCountType($asset['product_id'], $asset['product_model_id']);
            if (!$existsAsset) {
                $result = parent::add($asset);
                if (!$result['result']) {
                    return $result;
                }
                $asset = $result['data'];
            } else {
                $asset = $existsAsset;
                $result = $this->_result(true, $asset);
            }

            $updates[] = $asset;
        }

        $result['data'] = $updates;
        return $result;
    }

    /**
     * 入庫時に資産情報を更新する
     *  
     * - - -
     * 
     * @param string $assetId 資産ID
     * @param array $instock  入庫情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateInstock($assetId, $instock)
    {
        $asset = parent::get($assetId);
        if (!$asset || count($asset) === 0) {
            return $this->_invalid('資産管理情報が存在しない為、資産情報を更新することができませんでした。', ['method' => __METHOD__, 'asset_id' => $assetId]);
        }

        $sts = Configure::read('WNote.DB.Assets.AssetSts.stock');
        if ($instock['instock_kbn'] == Configure::read('WNote.DB.Instock.InstockKbn.repair')) {
            $sts = Configure::read('WNote.DB.Assets.AssetSts.repair');
        }

        $asset['asset_sts']       = $sts;
        $asset['current_user_id'] = null;

        $updateAsset = parent::save($asset->toArray());
        if (!$updateAsset['result']) {
            return $updateAsset;
        }

        // 使用者の更新
        $updateAssetUser = $this->ModelAssetUsers->updateInstock($asset['id'], $instock);
        if (!$updateAssetUser['result']) {
            return $addAssetUser;
        }

        return $updateAsset;
    }

    /**
     * 出庫予定より資産情報を更新する
     *  
     * - - -
     * 
     * @param string $assetId 資産ID
     * @param string $assetNo 資産管理番号
     * @param string $remarks 備考
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function saveByPicking($assetId, $assetNo, $remarks)
    {
        $asset = parent::get($assetId);
        if (!$asset || count($asset) === 0) {
            return $this->_invalid('資産管理情報が存在しない為、資産情報を更新することができませんでした。', ['method' => __METHOD__, 'asset_id' => $assetId]);
        }

        $asset['asset_no'] = $assetNo;
        $asset['remarks']  = $remarks;

        return parent::save($asset->toArray());
    }

    /**
     * 出庫時に資産情報を更新する
     *  
     * - - -
     * 
     * @param string $assetId 資産ID
     * @param array $picking  出庫情報
     * @param array $pickingPlan 出庫予定情報
     * @param array $stock    在庫情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updatePicking($assetId, $picking, $pickingPlan, $stock)
    {
        $asset = parent::get($assetId);
        if (!$asset || count($asset) === 0) {
            return $this->_invalid('資産管理情報が存在しない為、資産情報を更新することができませんでした。', ['method' => __METHOD__, 'asset_id' => $assetId]);
        }

        $sts = Configure::read('WNote.DB.Assets.AssetSts.use');
        if (intVal($stock['stock_count']) > 0) {
            $sts = Configure::read('WNote.DB.Assets.AssetSts.stock');
        }

        $asset['asset_sts']       = $sts;
        $asset['account_date']    = (!$asset['account_date'] || $asset['account_date'] === '') ? $picking['picking_date'] : $asset['account_date'];
        $asset['current_user_id'] = $pickingPlan['use_user_id'];

        $updateAsset = parent::save($asset->toArray());
        if (!$updateAsset['result']) {
            return $updateAsset;
        }

        // 使用者を追加
        $addAssetUser = $this->ModelAssetUsers->addUser($updateAsset['data'], $picking, $pickingPlan);
        if (!$addAssetUser['result']) {
            return $addAssetUser;
        }

        return $updateAsset;
    }

    /**
     * 資産を修理中に更新する
     *  
     * - - -
     * 
     * @param string $assetId 資産ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function repair($assetId)
    {
        $asset = parent::get($assetId);
        if (!$asset || count($asset) == 0) {
            return $this->_invalid('資産管理情報が存在しない為、資産情報を修理中に更新することができませんでした。', ['method' => __METHOD__, 'asset_id' => $assetId]);
        }

        // 資産状況を修理中に更新
        $asset['asset_sts'] = Configure::read('WNote.DB.Assets.AssetSts.repair');

        return parent::save($asset->toArray());
    }

    /**
     * 資産を修理中より在庫に更新する
     *  
     * - - -
     * 
     * @param string $assetId 資産ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function repairComplete($assetId)
    {
        $asset = parent::get($assetId);
        if (!$asset || count($asset) == 0) {
            return $this->_invalid('資産管理情報が存在しない為、資産情報を在庫に更新することができませんでした。', ['method' => __METHOD__, 'asset_id' => $assetId]);
        }

        // 資産状況を修理中に更新
        $asset['asset_sts'] = Configure::read('WNote.DB.Assets.AssetSts.stock');

        return parent::save($asset->toArray());
    }

    /**
     * 資産を修理中より廃棄予定に更新する
     *  
     * - - -
     * 
     * @param string $assetId 資産ID
     * @param string $abrogateReason 廃棄理由
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function repairAbrogate($assetId, $abrogateReason)
    {
        $asset = parent::get($assetId);
        if (!$asset || count($asset) == 0) {
            return $this->_invalid('資産管理情報が存在しない為、資産情報を在庫に更新することができませんでした。', ['method' => __METHOD__, 'asset_id' => $assetId]);
        }

        // 資産状況を廃棄予定に更新
        $asset['asset_sts']         = Configure::read('WNote.DB.Assets.AssetSts.abrogate_plan');
        $asset['abrogate_suser_id'] = $this->user();
        $asset['abrogate_reason']   = $abrogateReason;

        return parent::save($asset->toArray());
    }

    /**
     * 資産を廃棄済みに更新する
     *  
     * - - -
     * 
     * @param string $assetId 資産ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function abrogate($assetId)
    {
        $asset = parent::get($assetId);
        if (!$asset || count($asset) == 0) {
            return $this->_invalid('資産管理情報が存在しない為、資産情報を廃棄することができませんでした。', ['method' => __METHOD__, 'asset_id' => $assetId]);
        }

        // 資産状況を廃棄に更新
        $asset['asset_sts'] = Configure::read('WNote.DB.Assets.AssetSts.abrogate');

        return parent::save($asset->toArray());
    }

    /**
     * 資産を貸し出す
     *  
     * - - -
     * 
     * @param string $rental 貸出情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function rental($rental)
    {
        $asset = parent::get($rental['asset_id']);
        if (!$asset || count($asset) == 0) {
            return $this->_invalid('資産管理情報が存在しない為、資産貸出することができませんでした。', ['method' => __METHOD__, 'asset_id' => $assetId]);
        }

        $asset['asset_sts']       = Configure::read('WNote.DB.Assets.AssetSts.rental');
        $asset['current_user_id'] = $rental['user_id'];

        // 資産状況を貸出に更新
        $updateAsset = parent::save($asset->toArray());
        if (!$updateAsset['result']) {
            return $updateAsset;
        }

        // 使用者を追加
        $addAssetUser = $this->ModelAssetUsers->addUserAtRental($updateAsset['data'], $rental);
        if (!$addAssetUser['result']) {
            return $addAssetUser;
        }

        return $updateAsset;
    }

    /**
     * 資産を返却する
     *  
     * - - -
     * 
     * @param string $rental 貸出・返却情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function back($rental)
    {
        $asset = parent::get($rental['asset_id']);
        if (!$asset || count($asset) == 0) {
            return $this->_invalid('資産管理情報が存在しない為、資産貸出することができませんでした。', ['method' => __METHOD__, 'asset_id' => $assetId]);
        }

        $asset['asset_sts']       = Configure::read('WNote.DB.Assets.AssetSts.stock');
        $asset['current_user_id'] = null;

        // 資産状況を在庫に更新
        $updateAsset = parent::save($asset->toArray());
        if (!$updateAsset['result']) {
            return $updateAsset;
        }

        // 使用を利用終了に更新
        $addAssetUser = $this->ModelAssetUsers->updateBack($updateAsset['data']['id'], $rental);
        if (!$addAssetUser['result']) {
            return $addAssetUser;
        }

        return $updateAsset;
    }
}

