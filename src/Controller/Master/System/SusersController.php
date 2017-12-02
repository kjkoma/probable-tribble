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
namespace App\Controller\Master\System;

use App\Controller\AppController;

/**
 * SUsers Controller
 *
 */
class SusersController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('SysModelSusers');
    }

    /**
     * 初期画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->SysModelSusers->all();

        $this->set(compact('users'));
        $this->render();
    }
}
