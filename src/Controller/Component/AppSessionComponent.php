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
namespace App\Controller\Component;

use Cake\Core\Configure;

/**
 * This is generic component for session control.
 * 
 */
class AppSessionComponent extends AppComponent
{

/**
 * --------------------------
 * Controller sessions
 * --------------------------
 */
    /**
     * コントローラー固有のセッションを格納する
     *  
     * - - -
     * @param  mixed $value セッションに格納するオブジェクト
     * @return void
     */
    public function storeController($value)
    {
        $controller = $this->getController();
        $name = (empty($controller->name)) ? 'none' : $controller->name;
        $controller->request->getSession()->write('Controller.' . $name, $value);
    }

    /**
     * コントローラー固有のセッションを取得する
     *  
     * - - -
     * @return mixed セッションに格納されたオブジェクト
     */
    public function getControllerSession()
    {
        $controller = $this->getController();
        $value      = array();

        if ($controller->request->getSession()->check('Controller.' . $controller->name)) {
            $value = $controller->request->getSession()->read('Controller.' . $controller->name);
        }

        return $value;
    }

/**
 * --------------------------
 * Authentication sessions
 * --------------------------
 */
    /**
     * 認証情報をセッションに格納する
     *  
     * - - -
     * @param  \App\Controller\Component\AppUserComponent 認証ユーザーコンポーネント
     * @param  \App\Controller\Component\AppGlobalComponent グローバルデータコンポーネント
     * @return void
     */
    public function storeAuthentication($appUser, $appGlobal)
    {
        $user = $appUser->user();
        $session = $this->getController()->request->getSession();
        $session->write(Configure::read('WNote.Session.Auth.is_login')  , true);
        $session->write(Configure::read('WNote.Session.Auth.id')        , $user['id']);
        $session->write(Configure::read('WNote.Session.Auth.user')      , $appUser->toArray());
        $session->write(Configure::read('WNote.Session.App.global')     , $appGlobal->toArray());
    }

    /**
     * 認証ユーザー情報を取得する
     *  
     * - - -
     * @return array 認証ユーザーオブジェクトの配列
     */
    public function appUser()
    {
        return $this->getController()->request->getSession()->read(
            Configure::read('WNote.Session.Auth.user')
        );
    }

    /**
     * グローバルデータを取得する
     *  
     * - - -
     * @return array グローバルデータの配列
     */
    public function appGlobal()
    {
        return $this->getController()->request->getSession()->read(
            Configure::read('WNote.Session.App.global')
        );
    }

    /**
     * グローバルデータを更新する
     *  
     * - - -
     * @param  \App\Controller\Component\AppGlobalComponent グローバルデータコンポーネント
     */
    public function refreshAppGlobal($appGlobal)
    {
        $session = $this->getController()->request->getSession();
        $session->write(Configure::read('WNote.Session.App.global'), $appGlobal->toArray());
    }

    /**
     * セッションに格納された値を全て破棄する
     *  
     * - - -
     * @return void
     */
    public function destroyAuthentication()
    {
        $this->getController()->request->getSession()->destroy();
    }

    /**
     * セッションに格納された認証ユーザーを変更する
     *
     * - - -
     * @param \App\Controller\Component\AppUserComponent $appUser 認証ユーザー
     */
    public function changeAppUser($appUser)
    {
        $session = $this->getController()->request->getSession();
        $session->write(Configure::read('WNote.Session.Auth.user'), $appUser->toArray());
    }

}