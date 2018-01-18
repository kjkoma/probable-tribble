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
 * Instock Plans Controller
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
        $this->_loadComponent('SysModelSnames');
    }

    /**
     * 入庫予定（新規）画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function listNew()
    {
        $instockKbn = $this->SysModelSnames->byKey('INSTOCK_KBN');

        $this->set(compact('instockKbn'));
        $this->render();
    }

    /**
     * 入庫予定一覧画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function list()
    {
        $this->render();
    }

}
