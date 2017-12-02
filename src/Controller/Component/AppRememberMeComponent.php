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
 * This is generic component for remember me control.
 * 
 */
class AppRememberMeComponent extends AppComponent
{
    /**
     * 本クラスの初期化処理を行う
     *
     * - - -
     * @param array $config コンフィグ
     */
    public function initialize(array $config) {
        parent::initialize($config);
    }

    /**
     * ログイン状態を保存するかどうかを返す
     *  
     * - - -
     * @param  array $data リクエストデータ
     * @return boolean true: ログイン状態を保存する / false: ログイン状態を保存しない
     */
    public function isRememberMe($data)
    {
        if (!array_key_exists('rememberme', $data)) {
            return false;
        }

        $rememberme = $data['rememberme'];

        if ($rememberme == '1') {
            return true;
        }

        return false;
    }

    /**
     * Cookieにトークンを保存/更新する
     *
     * - - -
     * @param string $token
     * @return void
     */
    public function storeToken($token)
    {
        $this->getController()->Cookie->write(
            Configure::read('WNote.Cookie.rememberme'), serialize($token));
    }

    /**
     * Cookieよりトークンを削除する
     *
     * - - -
     * @return void
     */
    public function destroyToken()
    {
        $cookie = $this->getController()->Cookie;
        if ($cookie->check(Configure::read('WNote.Cookie.rememberme'))) {
            $cookie->delete(Configure::read('WNote.Cookie.rememberme'));
        }
    }

    /**
     * Cookieよりトークンを読み込む
     *  
     * - - -
     * @return string トークン / null
     */
    protected function readToken()
    {
        $cookie = $this->getController()->Cookie;
        if ($cookie->check(Configure::read('WNote.Cookie.rememberme'))) {
            return unserialize($cookie->read(Configure::read('WNote.Cookie.rememberme')));
        }

        return null;
    }

    /**
     * トークンよりユーザー情報を取得して自動ログインする
     * - - -
     * @return boolean true: ログイン成功 | false: ログイン失敗
     */
    public function autoLogin()
    {
        $token = $this->readToken();
        if (is_null($token)) {
            $this->AppLog->debug('Token is not found.', __CLASS__, __FUNCTION__);
            return false;
        }

        $query = $this->table("Susers")->find('valid')->where(['token =' => $token]);
        if ($query->count() === 0) {
            $this->AppLog->debug('System user is not found with token.', __CLASS__, __FUNCTION__);
            return false;
        }

        $suser = $query->first();
        if (!$suser) {
            $this->AppLog->debug('user is invalid.', __CLASS__, __FUNCTION__);
            return false;
        }

        $this->getController()->Auth->setUser($suser);
        $this->AppLog->debug('Recover authenticated user is success.', __CLASS__, __FUNCTION__);
        return true;
    }
}