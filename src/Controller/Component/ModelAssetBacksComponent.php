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
 * 資産返却（AssetBacks）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelAssetBacksComponent extends AppModelComponent
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
        $config['modelName'] = 'AssetBacks';
        parent::initialize($config);
    }

    /**
     * 資産返却情報を入庫予定詳細IDより取得する
     *  
     * - - -
     * @param integer $detailId 入庫予定詳細ID
     * @return \App\Model\Entity\AssetBack 資産返却情報
     */
    public function byInstockPlanDetailId($detailId)
    {
        return $this->modelTable->find('valid')
            ->where([
                'instock_plan_detail_id' => $detailId
            ])
            ->contain(['Assets' => function($q) {
                return $q->select(['id', 'asset_no', 'serial_no', 'asset_type']);
            }])
            ->contain(['AssetBacksReqOrganizations' => function($q) {
                return $q->select(['id', 'kname']);
            }])
            ->contain(['AssetBacksReqUsers' => function($q) {
                return $q->select(['id', 'sname', 'fname']);
            }])
            ->contain(['AssetBacksRcvSusers' => function($q) {
                return $q->select(['id', 'kname']);
            }])
            ->first();
    }

    /**
     * 資産返却情報を入庫予定詳細IDより取得する（資産返却情報のみ）
     *  
     * - - -
     * @param integer $detailId 入庫予定詳細ID
     * @return \App\Model\Entity\AssetBack 資産返却情報
     */
    public function onlyByInstockPlanDetailId($detailId)
    {
        return $this->modelTable->find('valid')
            ->where([
                'instock_plan_detail_id' => $detailId
            ])
            ->first();
    }

    /**
     * 返却資産を登録する
     *  
     * - - -
     * 
     * @param array $back 返却資産情報
     * @param array $detail 入庫予定詳細情報
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($back, $detail, $asset)
    {
        $back['domain_id']              = $this->current();
        $back['instock_plan_detail_id'] = $detail['id'];
        $back['instock_asset_id']       = $asset['id'];

        return parent::add($back);
    }

    /**
     * 返却資産を更新する
     *  
     * - - -
     * 
     * @param array $back 返却資産情報
     * @param array $detail 入庫予定詳細情報
     * @param array $asset 資産情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function update($back, $detail, $asset)
    {
        $check = $this->byInstockPlanDetailId($detail['id']);
        if (!$check || count($check) === 0) {
            return parent::_invalid('指定された返却情報がありません。', ['method' => __METHOD__, 'back' => $back, 'detail' => $detail]);
        }

        $back['id']               = $check['id'];
        $back['instock_asset_id'] = $asset['id'];

        return parent::save($back);
    }

    /**
     * 入庫時に返却資産を更新する
     *  
     * - - -
     * 
     * @param array $detailId  入庫予定詳細ID
     * @param array $instockId 入庫ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateInstock($detailId, $instockId)
    {
        $back = $this->onlyByInstockPlanDetailId($detailId);
        if (!$back || count($back) === 0) {
            return parent::_invalid('指定された返却情報がありません。', ['method' => __METHOD__, 'detailId' => $detailId, 'instockId' => $instockId]);
        }

        $back['instock_id'] = $instockId;

        return parent::save($back->toArray());
    }

    /**
     * 返却資産を削除する
     *  
     * - - -
     * 
     * @param array $detailId 入庫予定詳細ID
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function deleteByInstockPlanDetailId($detailId)
    {
        $back = $this->byInstockPlanDetailIdSimple($detailId);
        if (!$back || count($back) === 0) {
            return parent::_result(true, $detailId);
        }

        return parent::delete($back['id']);
    }
}
