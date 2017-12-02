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
use Cake\ORM\TableRegistry;
use Firebase\JWT\JWT;
use Cake\Utility\Security;

/**
 * 認証後のグローバルデータ取得などの初期処理を行う
 *  
 * 本コンポーネントで取得したデータはセッションに格納・復元する
 */
class AppGlobalComponent extends AppComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['SysModelDomains', 'SysModelSroles', 'SysModelSnames'];

    /** @var array $_domains ドメイン一覧 */
    private $_domains;

    /** @var array $_sroles システムロール一覧 */
    private $_sroles;

    /** @var array $_droles ドメインロール一覧 */
    private $_droles;

    /** @var array $_snames 名称データ */
    private $_snames = [];

    /** @var string $_jwt APIアクセス用JWT（アクセストークン） */
    private $_jwt;

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
    }

    /**
     * グローバルデータを作成する
     *  
     * - - -
     * @param \App\Controll\Component\AppUserComponent $appUser 認証ユーザー情報
     */
    public function create($appUser)
    {
        // ドメイン一覧
        $this->_createDomains();

        // システムロール一覧
        $this->_createSroles();

        // ドメインロール一覧
        $this->_createDroles();

        // 名称データ
        $this->_createSnames();

        // JWT
        $this->_createJWT($appUser);
    }

    /**
     * ドメイン一覧データを作成する
     *  
     * - - -
     */
    private function _createDomains()
    {
        $this->_domains = $this->SysModelDomains->valid(true);
    }

    /**
     * システムロール一覧データを作成する
     *  
     * - - -
     */
    private function _createSroles()
    {
        $this->_sroles = $this->SysModelSroles->systems(true);
    }

    /**
     * ドメインロール一覧データを作成する
     *  
     * - - -
     */
    private function _createDroles()
    {
        $this->_droles = $this->SysModelSroles->domains(true);
    }

    /**
     * 名称データを作成する
     *  
     * - - -
     */
    private function _createSnames()
    {
        $keys = [
            Configure::read('WNote.Names.dsts'),
            Configure::read('WNote.Names.dsts2'),
        ];

        foreach($keys as $key) {
            $this->_snames[$key] = $this->SysModelSnames->byKey($key, true);
        }
    }

    /**
     * APIアクセス用のJWTを作成する
     *  
     * - iss: JWT の発行者の識別子
     * - sub:  JWT の主語となる主体の識別子
     * - exp: JWT の有効期限
     * - ip: ログイン時のユーザーのIPアドレス
     * - current: 現在選択しているドメインのID
     * - - -
     * @param \App\Controll\Component\AppUserComponent $appUser 認証ユーザー情報
     */
    private function _createJWT($appUser)
    {
        $user = $appUser->user();
        if ($user && array_key_exists('token', $user)) {
            $this->_jwt = JWT::encode(
                [
                    'iss'     => Configure::read('WNote.App.site'),
                    'sub'     => $user['token'],
                    'exp'     => time() + Configure::read('WNote.JWT.expired'),
                    'ip'      => $appUser->ip(),
                    'current' => $appUser->current(),
                ],
                Security::salt()
            );
        }
    }

    /**
     * APIアクセス用のJWTを更新する
     *  
     * - - -
     * @param \App\Controll\Component\AppUserComponent $appUser 認証ユーザー情報
     */
    public function refreshJWT($appUser)
    {
        $this->_createJWT($appUser);
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
        foreach($this->_sroles as $srole) {
            if ($srole['kname'] == $kname) {
                return $srole['id'];
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
        foreach($this->_droles as $drole) {
            if ($drole['kname'] == $kname) {
                return $drole['id'];
            }
        }

        return -1;
    }

    /**
     * 本オブジェクトのデータを配列として取得する
     *  
     * - - -
     * @return array 本オブジェクトのデータ配列
     */
    public function toArray()
    {
        return [
            'domains' => $this->_domains,
            'sroles'  => $this->_sroles,
            'droles'  => $this->_droles,
            'snames'  => $this->_snames,
            'jwt'     => $this->_jwt,
        ];
    }

    /**
     * 本オブジェクトのデータを配列データより復元する
     *   注）本クラスのtoArrayメソッドで取得した配列以外では正しく復元できません。
     * - - -
     * @param array $array 本オブジェクトのデータ配列
     */
    public function fromArray($array)
    {
        if (!$array) {
            return;
        }

        if (array_key_exists('domains', $array) && !is_null($array['domains'])) {
            $this->_domains = $array['domains'];
        }

        if (array_key_exists('sroles', $array) && !is_null($array['sroles'])) {
            $this->_sroles = $array['sroles'];
        }

        if (array_key_exists('droles', $array) && !is_null($array['droles'])) {
            $this->_droles = $array['droles'];
        }

        if (array_key_exists('snames', $array) && !is_null($array['snames'])) {
            $this->_snames = $array['snames'];
        }

        if (array_key_exists('jwt', $array) && !is_null($array['jwt'])) {
            $this->_jwt = $array['jwt'];
        }
    }

}