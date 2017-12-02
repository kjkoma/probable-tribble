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
namespace App\Controller;

use App\Form\LoginForm;
use Cake\Event\Event;

/**
 * Index Controller
 *
 *
 */
class IndexController extends AppController
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loginForm = new LoginForm();
    }

    /**
     * アクション実行前の処理を行う
     *  
     * - - -
     * @param \Cake\Event\Event イベント
     * @return void
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow('index');
    }

    /**
     * トップページを表示する
     *
     * 認証済みの場合はログイン後のアクションへリダイレクトし、
     * 未認証の場合は、ログイン画面を表示する
     * - - -
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }
        $this->set('login', $this->loginForm);
        $this->viewBuilder()->enableAutoLayout(false);
        $this->render();
    }
}
