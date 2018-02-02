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
namespace App\Controller\Api\Exchange;

use \Exception;
use App\Controller\Api\ApiController;
use Cake\Core\Configure;

/**
 * Exchanges API Controller
 *
 */
class ApiExchangesController extends ApiController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelExchanges');
    }

    /**
     * 交換一覧を表示する
     *
     */
    public function search()
    {
        $data = $this->validateParameter('cond', ['post']);
        if (!$data) return;

        // 交換一覧を取得
        $exchanges = $this->ModelExchanges->search($data['cond']);

        // 交換表示用に編集する
        $list = [];
        foreach($exchanges as $exchange) {
            $list[] = [
                'id'                   => $exchange['id'],
                'req_date'             => $exchange['picking_plan']['req_date'],
                'req_user_name'        => $exchange['picking_plan']['picking_plan_req_user']['sname'] . ' ' . $exchange['picking_plan']['picking_plan_req_user']['fname'],
                'already_instock'      => ($exchange['instock_id'] == '') ? '未入庫' : '入庫済',
                'product_name'         => $exchange['exchanges_instock_asset']['product']['kname'],
                'asset_no'             => $exchange['exchanges_instock_asset']['asset_no'],
                'serial_no'            => $exchange['exchanges_instock_asset']['serial_no'],
                'picking_product_name' => $exchange['exchanges_picking_asset']['product']['kname'],
                'picking_asset_no'     => $exchange['exchanges_picking_asset']['asset_no'],
                'picking_serial_no'    => $exchange['exchanges_picking_asset']['serial_no'],
            ];
        }

        // レスポンスメッセージの作成
        $this->setResponse(true, 'your request is success', ['exchanges' => $list]);
    }
}
