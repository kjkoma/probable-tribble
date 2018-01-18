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
 * Assets API Controller
 *
 */
class ApiAssetsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelAssets');
    }

    /**
     * 資産情報を取得する
     *
     */
    public function asset()
    {
        $data = $this->validateParameter('asset_id', ['post']);
        if (!$data) return;

        // 資産情報を取得
        $asset = $this->ModelAssets->asset($data['asset_id']);

        // 表示用に編集する
        $asset['asset_type_name']     = $asset['asset_type_name']['name'];
        $asset['category_name']       = $asset['classification']['category']['kname'];
        $asset['classification_name'] = $asset['classification']['kname'];
        $asset['maker_name']          = $asset['company']['kname'];
        $asset['product_name']        = $asset['product']['kname'];
        $asset['product_model_name']  = ($asset['product_model']) ? $asset['product_model']['kname'] : '';
        $asset['asset_sts_name']      = $asset['asset_sts_name']['name'];
        $asset['asset_sub_sts_name']  = $asset['asset_sub_sts_name']['name'];
        $asset['created_user_name']   = $asset['asset_created_suser']['kname'];
        $asset['modified_user_name']  = $asset['asset_modified_suser']['kname'];

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['asset' => $asset]);
    }

    /**
     * 資産情報を検索する
     *
     */
    public function search()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 資産一覧を取得
        $assets = $this->ModelAssets->search($data['cond']);

        // 一覧表示用に編集する
        $list = []; $counter = 0; $limit = intVal(Configure::read('WNote.ListLimit.maxcount'));
        foreach($assets as $asset) {
            $list[] = [
                'id'                  => $asset['id'],
                'asset_type_name'     => $asset['asset_type_name']['name'],
                'asset_sts_name'      => $asset['asset_sts_name']['name'],
                'asset_sub_sts_name'  => $asset['asset_sub_sts_name']['name'],
                'kname'               => $asset['kname'],
                'classification_name' => $asset['classification']['kname'],
                'maker_name'          => $asset['company']['kname'],
                'product_name'        => $asset['product']['kname'],
                'serial_no'           => $asset['serial_no'],
                'asset_no'            => $asset['asset_no'],
                'current_user_name'   => ($asset['user']) ? $asset['user']['sname'] . ' ' . $asset['user']['fname'] : '',
            ];
            $counter++;
            if ($counter > $limit) break;  // 最大500件に制限する
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['assets' => $list]);
    }

    /**************************************************************************/
    /** 検証用メソッド                                                        */
    /**************************************************************************/
    /**
     * 指定されたシリアル番号の存在有無を検証する
     *
     */
    public function validateSerialNo()
    {
        $data = $this->validateParameter('serial_no', ['post']);
        if (!$data) return;

        $validate = true;

        // 資産
        $asset = $this->ModelAssets->bySerialNo($data['serial_no']);
        if (!$asset || count($asset) == 0) {
            $validate = false;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }

    /**
     * 指定された資産管理番号の存在有無を検証する
     *
     */
    public function validateAssetNo()
    {
        $data = $this->validateParameter('asset_no', ['post']);
        if (!$data) return;

        $validate = true;

        // 資産
        $asset = $this->ModelAssets->byAssetNo($data['asset_no']);
        if (!$asset || count($asset) == 0) {
            $validate = false;
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is succeed', ['validate' => $validate]);
    }

}

