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
namespace App\Controller\Api\Repair;

use \Exception;
use App\Controller\Api\ApiController;
use Cake\Core\Configure;

/**
 * Repairs API Controller
 *
 */
class ApiRepairsController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelRepairs');
    }

    /**
     * 修理一覧を表示する
     *
     */
    public function search()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 修理一覧を取得
        $repairs = $this->ModelRepairs->search($data['cond']);

        // 修理表示用に編集する
        $list = [];
        foreach($repairs as $repair) {
            $list[] = [
                'id'                  => $repair['id'],
                'repair_sts_name'     => $repair['repair_st']['name'],
                'req_date'            => $repair['picking_plan']['req_date'],
                'req_user_name'       => $repair['picking_plan']['picking_plan_req_user']['sname'] . ' ' . $repair['picking_plan']['picking_plan_req_user']['fname'],
                'category_name'       => $repair['repair_asset']['classification']['category']['kname'],
                'classification_name' => $repair['repair_asset']['classification']['kname'],
                'maker_name'          => $repair['repair_asset']['company']['kname'],
                'product_name'        => $repair['repair_asset']['product']['kname'],
                'asset_no'            => $repair['repair_asset']['asset_no'],
                'serial_no'           => $repair['repair_asset']['serial_no'],
                'trouble_kbn_name'    => $repair['repairs_trouble_kbn']['name']
            ];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['repairs' => $list]);
    }
}
