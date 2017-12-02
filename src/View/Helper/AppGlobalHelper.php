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
 * AppGlobalHelper
 * 
 * This helper is application global data helper for view.
 */
class AppGlobalHelper extends AppHelper
{
    /** @var array $_appGlobal グローバルデータの情報を格納した配列 */
    var $_appGlobal;

    /**
     * 初期化
     *  
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->_appGlobal = $this->sessionByConfig('WNote.Session.App.global');
    }

    /**
     * ドメイン一覧を取得する
     *  
     * - - -
     * @return array ドメイン一覧
     */
    public function domains()
    {
        return $this->nArray(
            $this->_appGlobal,
            'domains'
        );
    }

    /**
     * システムロール一覧を取得する
     *  
     * - - -
     * @return array システムロール一覧
     */
    public function sroles()
    {
        return $this->nArray(
            $this->_appGlobal,
            'sroles'
        );
    }

    /**
     * ドメインロール一覧を取得する
     *  
     * - - -
     * @return array ドメインロール一覧
     */
    public function droles()
    {
        return $this->nArray(
            $this->_appGlobal,
            'droles'
        );
    }

    /**
     * 名称データ（dsts）を取得する
     *  
     * - - -
     * @return array 名称データ（dsts）
     */
    public function dsts()
    {
        return $this->nArray(
            $this->_appGlobal,
            ['snames', $this->conf('WNote.Names.dsts')]
        );
    }

    /**
     * 名称データ（dsts-2）を取得する
     *  
     * - - -
     * @return array 名称データ（dsts-2）
     */
    public function dsts2()
    {
        return $this->nArray(
            $this->_appGlobal,
            ['snames', $this->conf('WNote.Names.dsts2')]
        );
    }

    /**
     * APIアクセス用のJWTを取得する
     *  
     * - - -
     * @return string APIアクセス用のJWT
     */
    public function jwt()
    {
        return $this->nArray(
            $this->_appGlobal,
            'jwt'
        );
    }

    /**
     * 指定されたドメインIDのドメイン情報を返す
     *  
     * - - -
     * @param integer $domainId ドメインID
     * @return array|null ドメイン情報
     */
    public function hasDomain($domainId)
    {
        $domains = $this->domains();

        if ($domains) {
            foreach ($domains as $domain) {
                if ($domain['id'] == $domainId) {
                    return $domain;
                }
            }
        }

        return null;
    }

    /**
     * 指定したシステムロールの識別子（kname）のIDを取得する
     *  
     * - - -
     * @param string $kname システムロールの識別子
     * @param integer システムロールのID
     */
    public function systemRoleId($kname)
    {
        $sroles = $this->sroles();

        if ($sroles) {
            foreach ($sroles as $srole) {
                if ($srole['kname'] == $kname) {
                    return $srole['id'];
                }
            }
        }

        return -1;
    }

    /**
     * 指定したドメインロールの識別子（kname）のIDを取得する
     *  
     * - - -
     * @param string $kname システムロールの識別子
     * @param integer システムロールのID
     */
    public function domainRoleId($kname)
    {
        $droles = $this->droles();

        if ($droles) {
            foreach ($droles as $drole) {
                if ($drole['kname'] == $kname) {
                    return $drole['id'];
                }
            }
        }

        return -1;
    }
}