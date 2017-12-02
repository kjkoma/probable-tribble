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
 */
namespace App\Controller;

use App\Form\LoginForm;
use Cake\Event\Event;

/**
 * 認証を行うコントローラークラス
 *  
 * ログインフォームより入力された認証情報をもとに認証を行う。
 *  
 */
class AuthController extends AppController
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
        $this->loginForm  = new LoginForm();
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
        $this->Auth->allow(['login', 'logout']);
    }

    /**
     * ログインする
     *  
     * 認証済みの場合はログイン後のアクションへリダイレクトし、
     * 未認証の場合は、emailとpasswordで認証を行う。
     * - - -
     * @return \Cake\Http\Response|null ログイン後のリダイレクト先、または、トップ画面
     */
     public function login()
     {
         $redirectDefault = ['controller' => 'Index', 'action' => 'index'];

         // 認証済の場合
         if ($this->Auth->user()) {
             return $this->redirect($this->Auth->redirectUrl());
         }

         // 未認証の場合
         if ($this->request->is('post')) {

             // バリデーション
             if (!$this->loginForm->validate($this->request->getData())) {
                 $this->Flash->error(__('入力された内容に誤りがあります。再度入力してください。'), [ 'key' => 'auth' ]);
                 $this->setError($this->loginForm->errors());
                 return $this->redirect($redirectDefault);
             }

             // 認証
             $user = $this->Auth->identify();
             if (!$user) {
                 $this->AppLog->auth($this->request, "faild"); // logging
                 $this->Flash->error(__('Emailアドレス、または、パスワードが正しくありません。'), [ 'key' => 'auth' ]);
                 return $this->redirect($redirectDefault);
             }
             $this->AppLog->auth($this->request, "success"); // logging

             // 認証ユーザーのアプリケーション属性情報の作成
             $this->AppUser->create($user);
             if (!$this->AppUser->validate()) {
                 $this->AppLog->auth($this->request, "faild"); // logging
                 $this->Flash->error(__('指定されたユーザーの情報がありません。本システムの運用担当者にお問い合わせください。'), [ 'key' => 'auth' ]);
                 return $this->redirect($redirectDefault);
             }

             // グローバルデータの作成
             $this->AppGlobal->create($this->AppUser);

             // ログイン成功
             $this->AppLog->login($user); // logging
             $this->AppSession->storeAuthentication($this->AppUser, $this->AppGlobal);
             $this->Auth->setUser($user);

             // Remember Me
             $this->AppRememberMe->destroyToken();
             if ($this->AppRememberMe->isRememberMe($this->request->getData())) {
                 $this->AppRememberMe->storeToken($user['token']);
             }
             return $this->redirect($this->Auth->redirectUrl());
         }

         return $this->redirect($redirectDefault);
     }

    /**
     * ログアウトする
     *  
     * セッション情報を破棄し、ログイン画面へ遷移する。
     * - - -
     * @return \Cake\Http\Response|null ログアウト後のリダイレクト先
     */
     public function logout()
     {
         $this->AppLog->logout($this->AppUser->user()); // logging
         $this->AppRememberMe->destroyToken();
         $this->AppSession->destroyAuthentication();
         return $this->redirect($this->Auth->logout());
     }

}
