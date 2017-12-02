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
namespace App\View\Helper;

/**
 * AppUserHelper
 * 
 * This helper is application user information helper for view.
 */
class AppUserHelper extends AppHelper
{
    /** @var array $_appUser 認証ユーザーの情報を格納した配列 */
    var $_appUser;

    /**
     * 初期化
     *  
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->_appUser = $this->sessionByConfig('WNote.Session.Auth.user');
    }

    /**
     * 認証ユーザーのIDを取得する
     *
     * - - -
     * @return string 認証ユーザーのID
     */
    public function id()
    {
        return $this->bArray(
            $this->_appUser,
            ['user', 'id']
        );
    }

    /**
     * 認証ユーザーの表示名を取得する
     *  
     * - - -
     * @return string 認証ユーザーの表示名
     */
    public function kname()
    {
        return $this->bArray(
            $this->_appUser,
            ['user', 'kname']
        );
    }

    /**
     * 認証ユーザーのメールアドレスを取得する
     *
     * - - -
     * @return string 認証ユーザーのメールアドレス
     */
    public function email()
    {
        return $this->bArray(
            $this->_appUser,
            ['user', 'email']
        );
    }

    /**
     * 認証ユーザーの現在のドメインIDを取得する
     *
     * - - -
     * @return integer 現在のドメインID
     */
    public function domain()
    {
        return $this->nArray(
            $this->_appUser,
            'current'
        );
    }

    /**
     * 認証ユーザーが権限を持つドメイン一覧を取得する
     *
     * - - -
     * @return array 権限を持つドメイン一覧
     */
    public function domains()
    {
        return $this->nArray(
            $this->_appUser,
            'domains'
        );
    }

    /**
     * 認証ユーザーのログイン有無を取得する
     *
     * - - -
     * @return boolean true:ログイン中|false:未ログイン
     */
    public function isLogin()
    {
        $is_login = $this->sessionByConfig('WNote.Session.Auth.is_login');

        if (!$is_login) {
            return false;
        }

        return $is_login;
    }

    /**
     * 認証ユーザーがシステム管理者かどうかを判定する
     *
     * - - -
     * @return boolean true:システム管理者|false:システム管理者以外
     */
    public function isAdmin()
    {
        $roleKname = $this->nArray(
            $this->_appUser,
            'roleKname'
        );

        if ($roleKname == $this->conf('WNote.DB.Sroles.Kname.wnoteadmin')
            || $roleKname == $this->conf('WNote.DB.Sroles.Kname.sysadmin')) {
            return true;
        }

        return false;
    }

    /**
     * 指定されたドメインが現在のドメインかどうかを判定する
     *
     * - - -
     * @param integer $domainId ドメインID
     * @return boolean true:現在のドメイン|false:現在のドメイン以外
     */
    public function isCurrentDomain($domainId)
    {
        if ($this->domain() == $domainId) {
            return true;
        }
        return false;
    }

    /**
     * 指定されたドメインに対する権限があるかどうかを判定する
     *
     * - - -
     * @param integer $domainId ドメインID
     * @return boolean true:権限がある|false:権限がない
     */
    public function hasDomain($domainId)
    {
        $domains = $this->domains();

        if ($domains) {
            foreach ($domains as $domain) {
                if ($domain['domain_id'] == $domainId) {
                    return true;
                }
            }
        }

        return false;
    }

}