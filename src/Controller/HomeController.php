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

use \Cake\Event\Event;

/**
 * Home Controller
 *
 *
 * @method Home[] paginate($object = null, array $settings = [])
 */
class HomeController extends AppController
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
    }

    /**
     * ホーム画面を表示する
     *
     * @return \Cake\Http\Response|void
     */
    public function home()
    {
        $this->render();
    }

    /**
     * ドメインを変更する
     *
     * @return \Cake\Http\Response|void
     */
    public function changeDomain()
    {
        if ($this->request->is('post'))
        {
            $data = $this->request->getData();
            if ($data && array_key_exists('to_domain_id', $data)) {
                $this->AppUser->changeCurrent($data['to_domain_id']);
                $this->AppSession->changeAppUser($this->AppUser);
            }
        }

        $this->redirect(['action' => 'home']);
    }

}
