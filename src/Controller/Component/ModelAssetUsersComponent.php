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

/**
 * 資産利用者（AssetUsers）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelAssetUsersComponent extends AppModelComponent
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
        $config['modelName'] = 'AssetUsers';
        parent::initialize($config);
    }

    /**
     * 資産IDより最新の資産利用者情報を取得する
     *  
     * - - -
     * 
     * @param string $assetId 資産ID
     * @return \App\Model\Entity\AssetUser 資産利用者
     */
    public function currentUser($assetId)
    {
        $query = $this->modelTable->find('valid');
        $result = $query
            ->select(['start_date' => $query->func()->max('start_date')])
            ->where(['asset_id' => $assetId])
            ->first();
        if (!$result || count($result) == 0) return false;

        $query = $this->modelTable->find('valid')
            ->andWhere(['asset_id'   => $assetId])
            ->andWhere(['start_date' => $result['start_date']]);

        return $query->first();
    }

    /**
     * 資産利用者を登録する
     *  
     * - - -
     * 
     * @param string $asset 資産情報
     * @param array $picking  出庫情報
     * @param array $plan     出庫予定情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addUser($asset, $picking, $plan)
    {
        // 資産利用者登録
        $user = [];
        $user['domain_id']   = $asset['domain_id'];
        $user['asset_id']    = $asset['id'];
        $user['user_id']     = $plan['use_user_id'];
        $user['start_date']  = $picking['picking_date'];
        $user['useage_type'] = Configure::read('WNote.DB.Assets.AssetUseageType.normal');
        $user['useage_sts']  = Configure::read('WNote.DB.Assets.AssetUseageSts.use');
        $user['picking_id']  = $picking['id'];

        return parent::add($user);
    }

    /**
     * 入庫による資産利用者を更新する
     *  
     * - - -
     * 
     * @param string $assetId 資産ID
     * @param array $instock  入庫情報
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function updateInstock($assetId, $instock)
    {
        $user = $this->currentUser($assetId);
        if (!$currentUser) {
            return $this->_result(true, null);
        }

        $user['end_date']   = $instock['instock_date'];
        $user['useage_sts'] = Configure::read('WNote.DB.Assets.AssetUseageSts.end');
        $user['instock_id'] = $instock['id'];

        return parent::save($user->toArray());
    }
}

