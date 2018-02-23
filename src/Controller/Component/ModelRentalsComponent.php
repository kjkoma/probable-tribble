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
use Cake\ORM\Query;

/**
 * 貸出（Rentals）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelRentalsComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['ModelAssets'];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'Rentals';
        parent::initialize($config);
    }

    /**
     * 貸出予定を取得する
     *  
     * - - -
     * 
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 貸出予定一覧（ResultSet or Array）
     */
    public function plans($toArray = false)
    {
        $query = $this->modelTable->find('validAll');
        $query = $this->_addAssetAssociation($query);
        $query
            ->where([
                'rental_sts' => Configure::read('WNote.DB.Rental.RentalSts.plan')
            ])
            ->order([
                'Assets.classification_id' => 'ASC',
                'Assets.maker_id'          => 'ASC',
                'Assets.product_id'        => 'ASC',
                'Assets.product_model_id'  => 'ASC'
            ]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 貸出中一覧を取得する
     *  
     * - - -
     * 
     * @param array $cond 検索条件
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 貸出中一覧（ResultSet or Array）
     */
    public function rentals($cond, $toArray = false)
    {
        $query = $this->modelTable->find('validAll');
        $query = $this->_addAssetAssociation($query);
        $query
            ->contain([
                'RentalUsers' => function($q) {
                    return $q->select([ 'id', 'fname', 'sname' ]);
                }
            ])
            ->contain([
                'RentalAdminUsers' => function($q) {
                    return $q->select([ 'id', 'fname', 'sname' ]);
                }
            ])
            ->contain([
                'RentalSusers' => function($q) {
                    return $q->select([ 'id', 'kname' ]);
                }
            ])
            ->where([
                'rental_sts' => Configure::read('WNote.DB.Rental.RentalSts.rental')
            ])
            ->order([
                'Rentals.user_id'          => 'ASC',
                'Rentals.admin_user_id'    => 'ASC',
                'Assets.classification_id' => 'ASC',
                'Assets.maker_id'          => 'ASC',
                'Assets.product_id'        => 'ASC',
                'Assets.product_model_id'  => 'ASC'
            ]);

        $hasCondition = false;
        if (array_key_exists('user_id', $cond) && $cond['user_id'] !== '') {
            $query->where(['user_id' => $cond['user_id']]);
            $hasCondition = true;
        }
        if (array_key_exists('admin_user_id', $cond) && $cond['admin_user_id'] !== '') {
            $query->where(['admin_user_id' => $cond['admin_user_id']]);
            $hasCondition = true;
        }
        if (array_key_exists('asset_no', $cond) && $cond['asset_no'] !== '') {
            $query->where(['Assets.asset_no' => $cond['asset_no']]);
            $hasCondition = true;
        }

        if (!$hasCondition) {
            $query->andWhere(['1=0']); // 検索結果を取得しない
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 修理情報を検索する
     *  
     * - - -
     * @param array $cond 検索条件
     * @param array $enableLimit 制限有無（true: 制限あり／false: 制限なし）
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 修理一覧（ResultSet or Array）
     */
    public function search($cond, $enableLimit = false, $toArray = false)
    {
        $query = $this->modelTable->find('validAll');
        $query = $this->_addAssetAssociation($query);
        $query = $this->_addRentalAssociation($query);
        $query
            ->order([
                'Rentals.rental_date'      => 'DESC',
                'Rentals.back_date'        => 'DESC',
                'Rentals.user_id'          => 'ASC',
                'Rentals.admin_user_id'    => 'ASC',
                'Assets.asset_type'        => 'ASC',
                'Assets.asset_no'          => 'ASC'
            ]);

        $hasCondition = false;
        if (array_key_exists('rental_sts', $cond) && $cond['rental_sts'] !== '') {
            $query->where(['rental_sts' => $cond['rental_sts']]);
            $hasCondition = true;
        }
        if (array_key_exists('user_id', $cond) && $cond['user_id'] !== '') {
            $query->where(['user_id' => $cond['user_id']]);
            $hasCondition = true;
        }
        if (array_key_exists('admin_user_id', $cond) && $cond['admin_user_id'] !== '') {
            $query->where(['admin_user_id' => $cond['admin_user_id']]);
            $hasCondition = true;
        }
        if (array_key_exists('back_user_id', $cond) && $cond['back_user_id'] !== '') {
            $query->where(['back_user_id' => $cond['back_user_id']]);
            $hasCondition = true;
        }
        if (array_key_exists('rental_date_from', $cond) && $cond['rental_date_from'] !== '') {
            $query->where(['rental_date_from >=' => $cond['rental_date_from']]);
            $hasCondition = true;
        }
        if (array_key_exists('rental_date_to', $cond) && $cond['rental_date_to'] !== '') {
            $query->where(['rental_date_to <=' => $cond['rental_date_to']]);
            $hasCondition = true;
        }
        if (array_key_exists('back_date_from', $cond) && $cond['back_date_from'] !== '') {
            $query->where(['rental_date_from >=' => $cond['rental_date_from']]);
            $hasCondition = true;
        }
        if (array_key_exists('back_date_to', $cond) && $cond['back_date_to'] !== '') {
            $query->where(['rental_date_to <=' => $cond['rental_date_to']]);
            $hasCondition = true;
        }

        if ($enableLimit && !$hasCondition) {
            $query->andWhere(['1 = 0']);
        }

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * (private)指定されたクエリビルダに関連モデルを追加する
     *  
     * - - -
     * @param Cake\ORM\Query クエリビルダ
     * @return Cake\ORM\Query クエリビルダ
     */
    private function _addRentalAssociation(Query $query) {
        return $query
            ->contain([
                'RentalReqUsers' => function($q) {
                    return $q->select([ 'id', 'fname', 'sname' ]);
                }
            ])
            ->contain([
                'RentalUsers' => function($q) {
                    return $q->select([ 'id', 'fname', 'sname' ]);
                }
            ])
            ->contain([
                'RentalAdminUsers' => function($q) {
                    return $q->select([ 'id', 'fname', 'sname' ]);
                }
            ])
            ->contain([
                'RentalSusers' => function($q) {
                    return $q->select([ 'id', 'kname' ]);
                }
            ])
            ->contain([
                'RentalBackUsers' => function($q) {
                    return $q->select([ 'id', 'fname', 'sname' ]);
                }
            ])
            ->contain([
                'RentalBackSusers' => function($q) {
                    return $q->select([ 'id', 'kname' ]);
                }
            ])
            ->contain([
                'RentalStsName' => function($q) {
                    return $q->select([ 'nid', 'name' ]);
                }
            ]);
    }

    /**
     * (private)指定されたクエリビルダに資産関連の関連モデルを追加する
     *  
     * - - -
     * @param Cake\ORM\Query クエリビルダ
     * @return Cake\ORM\Query クエリビルダ
     */
    private function _addAssetAssociation(Query $query) {
        return $query
            ->contain([
                'Assets' => function($q) {
                    return $q->select([
                        'id', 'asset_type', 'classification_id', 'maker_id',
                        'product_id', 'product_model_id', 'kname', 'serial_no', 'asset_no'
                    ]);
                }
            ])
            ->contain([
                'Assets.AssetTypeName' => function($q) {
                    return $q->select([ 'nkey', 'nid', 'name' ]);
                }
            ])
            ->contain([
                'Assets.Classifications' => function($q) {
                    return $q->select([ 'id', 'kname' ]);
                }
            ])
            ->contain([
                'Assets.Companies' => function($q) {
                    return $q->select([ 'id', 'kname' ]);
                }
            ])
            ->contain([
                'Assets.Products' => function($q) {
                    return $q->select([ 'id', 'kname' ]);
                }
            ])
            ->contain([
                'Assets.ProductModels' => function($q) {
                    return $q->select([ 'id', 'kname' ]);
                }
            ]);
    }

    /**
     * 指定された資産の貸出予定を取得する
     *  
     * - - -
     * @param array $assetId 資産ID
     * @return \App\Model\Entity\Rental 貸出情報
     */
    public function planByAssetId($assetId)
    {
        return $this->modelTable->find('valid')
            ->where([
                'asset_id'   => $assetId,
                'rental_sts' => Configure::read('WNote.DB.Rental.RentalSts.plan')
            ])
            ->first();
    }

    /**
     * 資産IDより該当する資産データを取得する
     *  
     * - - -
     * @param string assetId 資産ID
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 貸出一覧（ResultSet or Array）
     */
    public function listByAssetId($assetId, $toArray = false)
    {
        $query = $this->modelTable->find('validAll');
        $query = $this->_addRentalAssociation($query);
        $query
            ->where([
                'asset_id' => $assetId
            ])
            ->order([
                'Rentals.rental_date'      => 'DESC',
                'Rentals.back_date'        => 'DESC',
                'Rentals.user_id'          => 'ASC',
                'Rentals.admin_user_id'    => 'ASC'
            ]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 貸出予定資産を追加する
     *  
     * - - -
     * @param array $assetId 資産ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addAsset($assetId)
    {
        // 資産情報を取得
        $asset = $this->ModelAssets->get($assetId);
        if (!$asset || count($asset) == 0) {
            return $this->_invalid('資産情報が存在しないため、貸出予定に追加できませんでした。');
        }

        // 該当資産の貸出予定を取得（重複チェック）
        $rental = $this->planByAssetId($assetId);
        if (!$rental || count($rental) == 0) {
            // 貸出予定情報を登録
            $new = [];
            $new['domain_id']  = $this->current();
            $new['asset_id']   = $assetId;
            $new['rental_sts'] = Configure::read('WNote.DB.Rental.RentalSts.plan');
            return parent::add($new);
        }

        return $this->_result(true, $rental);
    }

    /**
     * 予定資産の貸出を行う
     *  
     * - - -
     * @param array $entry 貸出情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function rental($entry)
    {
        // 貸出情報を取得
        $rental = parent::get($entry['id']);
        if (!$rental || count($rental) == 0) {
            return $this->_invalid('貸出予定が存在しないため、貸出できませんでした。');
        }

        $rental['rental_sts']      = Configure::read('WNote.DB.Rental.RentalSts.rental');
        $rental['req_date']        = $entry['req_date'];
        $rental['req_user_id']     = $entry['req_user_id'];
        $rental['plan_date']       = $entry['plan_date'];
        $rental['admin_user_id']   = $entry['admin_user_id'];
        $rental['user_id']         = $entry['user_id'];
        $rental['rental_remarks']  = $entry['rental_remarks'];
        $rental['rental_date']     = $this->today();
        $rental['rental_suser_id'] = $this->user();
        $rental['back_plan_date']  = $entry['back_plan_date'];

        // 貸出情報を更新
        return parent::save($rental->toArray());
    }

    /**
     * 予定資産の返却を行う
     *  
     * - - -
     * @param array $entry 貸出情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function back($entry)
    {
        // 貸出情報を取得
        $rental = parent::get($entry['id']);
        if (!$rental || count($rental) == 0) {
            return $this->_invalid('貸出情報が存在しないため、返却できませんでした。');
        }

        $rental['rental_sts']      = Configure::read('WNote.DB.Rental.RentalSts.back');
        $rental['back_date']       = $entry['back_date'];
        $rental['back_user_id']    = $entry['back_user_id'];
        $rental['back_suser_id']   = $entry['back_suser_id'];
        $rental['remarks']         = $entry['remarks'];

        // 貸出情報を更新
        return parent::save($rental->toArray());
    }

}
