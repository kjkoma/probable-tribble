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
namespace App\Controller\Api\Asset;

use \Exception;
use App\Controller\Api\ApiController;
use Cake\Core\Configure;

/**
 * Asset Users API Controller
 *
 */
class ApiAssetUsersController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelAssetUsers');
    }

    /**
     * 送信された資産IDの利用者履歴を取得する
     *
     */
    public function users()
    {
        $data = $this->validateParameter('asset_id', ['post']);
        if (!$data) return;

        // 利用者一覧を取得
        $users = $this->ModelAssetUsers->users($data['asset_id']);

        // 一覧表示用に編集する
        foreach($users as $user) {
            $user['useage_sts_name']  = $user['asset_users_useage_sts_name']['name'];
            $user['user_name']        = $user['user']['sname'] . ' ' . $user['user']['fname'];
            $user['admin_user_name']  = ($user['asset_admin_user']) ? $user['asset_admin_user']['sname'] . ' ' . $user['asset_admin_user']['fname'] : '';
            $user['useage_type_name'] = $user['asset_users_useage_type_name']['name'];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['users' => $users]);
    }

}

