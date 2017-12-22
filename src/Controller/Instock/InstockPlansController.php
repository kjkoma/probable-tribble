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
namespace App\Controller\Instock;

use App\Controller\AppController;

/**
 * SUsers Controller
 *
 */
class InstockPlansController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->_loadComponent('ModelInstockPlans');
        $this->_loadComponent('SysModelSnames');
    }

    /**
     * 入庫予定（新規）画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function listNew()
    {
        $plans      = $this->ModelInstockPlans->listNew();
        $instockKbn = $this->SysModelSnames->byKey('INSTOCK_KBN');
        $instockSts = $this->SysModelSnames->byKey('INSTOCK_STS');

        $this->set(compact('plans', 'instockKbn', '$instockSts'));
        $this->render();
    }

    /**
     * 入庫予定一覧画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function list()
    {
        $plans      = $this->ModelInstockPlans->list();
        $instockKbn = $this->SysModelSnames->byKey('INSTOCK_KBN');
        $instockSts = $this->SysModelSnames->byKey('INSTOCK_STS');

        $this->set(compact('plans', 'instockKbn', '$instockSts'));
        $this->render();
    }

    /**
     * 入庫予定詳細画面（新規用）を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function detailNew()
    {
        $data = $this->validateParameter('instock_plan_id', ['POST']);

        $plans = $this->ModelInstockPlans->detailNew($data['instock_plan_id']);

        $this->set(compact('plans'));
        $this->render();
    }

    /**
     * 入庫予定詳細画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function detail()
    {
        $data = $this->validateParameter('instock_plan_id', ['POST']);

        $plans = $this->ModelInstockPlans->detail($data['instock_plan_id']);

        $this->set(compact('plans'));
        $this->render();
    }

    /**
     * 入庫予定一覧を検索する
     *
     * @return \Cake\Http\Response|void
     */
    public function seach()
    {
        $data = $this->validateParameter('cond', 'POST');

        $plans = $this->ModelInstockPlans->search($data['cond']);

        $this->set(compact('plans'));
        $this->render();
    }

}
