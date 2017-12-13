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
 * 本アプリケーションの認証ユーザーに関わる情報を保有するコンポーネント
 *  
 * 本コンポーネントのデータはログイン時に作成される。以下のデータのみログイン時以外にも更新されます。
 *  - $_current 現在のドメイン
 * 
 */
class AppUserComponent extends AppComponent
{
    /** @var array $components 利用コンポーネント */
    public $components = ['SysModelSusers', 'SysModelSuserDomains'];

    /** @var string ログイン時のIPアドレス */
    private $_ip;

    /** @var \App\Model\Entity\SUser $_user 認証ユーザー */
    private $_user;

    /** @var array $_domains 選択可能なドメイン配列（配列要素 - \App\Model\Entity\SuserDomain） */
    private $_domains;

    /** @var integer $_current 現在のドメイン */
    private $_current;

    /** @var string $_roleKName システム権限の識別名 */
    private $_roleKname;

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
     * ログイン時のIPアドレスを取得する
     *  
     * - - -
     * @return string IPアドレス
     */
    public function ip()
    {
        return $this->_ip;
    }

    /**
     * ユーザーを取得する
     *  
     * - - -
     * @return \App\Model\Entity\SUser ユーザー
     */
    public function user()
    {
        return $this->_user;
    }

    /**
     * ユーザーの利用可能なドメインを取得する
     *  
     * - - -
     * @return array \App\Model\Entity\SusersDomainを含む配列
     */
    public function domains()
    {
        return $this->_domains;
    }

    /**
     * 現在のドメインを取得する
     *  
     * - - -
     * @return integer 現在のドメインID
     */
    public function current()
    {
        return $this->_current;
    }

    /**
     * システムロールの識別名を取得する
     *  
     * - - -
     * @return string システムロールの識別名
     */
    public function roleKname()
    {
        return $this->_roleKname;
    }

    /**
     * 指定されたユーザーを元に本オブジェクトの情報を作成する
     *  
     * ※ログイン時にのみ呼び出すこと
     * 
     * - - -
     * @param \App\Model\Entity\SUser $user
     */
    public function create($user)
    {
        $this->_user = $user;
        $this->_ip   = $_SERVER['REMOTE_ADDR'];

        // 利用可能ドメイン取得
        $this->_createDomains($this->_user['id']);

        // 初回はデフォルトドメインをカレントドメインとして設定
        $this->_createCurrent($this->_domains);

        // システムロールの識別名を取得
        $this->_createSroleKname($this->_user['srole_id']);
    }

    /**
     * 指定されたJWTを元に本オブジェクトの情報を作成する
     *  
     * - - -
     * @param array $jwt API経由で取得したJWT
     */
    public function createByJWT($jwt)
    {
        $token   = $jwt['sub'];
        $current = $jwt['current'];

        $this->_user = $this->SysModelSusers->findByToken($token);
        $this->_ip   = $jwt['ip'];

        if ($this->_user && !is_null($current)) {
            // 利用可能ドメイン取得
            $this->_createDomains($this->_user['id']);

            // 初回はデフォルトドメインをカレントドメインとして設定
            $this->_createCurrent($this->_domains, $current);

            // システムロールの識別名を取得
            $this->_createSroleKname($this->_user['srole_id']);
        }
    }

    /**
     * 指定されたシステムユーザーの利用可能ドメイン一覧を作成する
     *  
     * - - -
     * @param integer $suserId システムユーザーID
     */
    private function _createDomains($suserId)
    {
        // 利用可能ドメイン取得
        $this->_domains = $this->SysModelSuserDomains->findBySuserId($suserId, true);
    }

    /**
     * カレントドメインを作成する
     *  
     * - - -
     * @param array $domains ドメイン一覧
     * @param integer|null $current カレントドメイン（初回生成時は指定不要）
     */
    private function _createCurrent($domains, $current = null)
    {
        foreach($domains as $d) {
            if ($d->default_domain == Configure::read('WNote.DB.SuserDomains.DefaultDomain.default')) {
                $this->_current = $d->domain_id;
            }
        }
    }

    /**
     * 指定されたユーザーのシステムロールの識別名を作成する
     *  
     * - - -
     * @param integer $sroleId システムロールID
     */
    private function _createSroleKname($sroleId)
    {
        // システムロールの識別名を取得
        $sroles =$this->table("Sroles")
            ->findById($sroleId)
            ->first();

        $this->_roleKname = $sroles['kname'];
    }

    /**
     * 本オブジェクトの情報が正常に設定されているかどうかを判定する
     *  
     * - - -
     * @return boolean true:正常|false:正しく情報が設定できていない
     */
    public function validate()
    {
        if (is_null($this->domains()) || count($this->domains()) === 0 ) {
            return false;
        }

        if (is_null($this->current()) || empty($this->current())) {
            return false;
        }

        if (is_null($this->roleKname()) || empty($this->roleKname())) {
            return false;
        }

        return true;
    }

    /**
     * 対象のドメインが現在のドメインかどうかを比較する
     *  
     * - - -
     * @param integer $domainId 比較するドメインID
     * @return boolean true:現在のドメイン|false:現在のドメイン以外
     */
    public function isCurrent($domainId)
    {
        if ($this->current() == $domainId) {
            return true;
        }

        return false;
    }

    /**
     * システムロール上の管理者かどうかを返す
     *  
     * - - -
     * @return boolean true:管理者|false:管理者以外
     */
    public function isAdmin()
    {
        if ($this->roleKname() == Configure::read('WNote.DB.Sroles.Kname.wnoteadmin')
            || $this->roleKname() == Configure::read('WNote.DB.Sroles.Kname.sysadmin')) {
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

    /**
     * 現在のドメインを変更する
     *  
     * - - -
     * @param integer $domainId 変更後のドメインID
     * @return boolean true:変更|false:未変更（変更不可等）
     */
    public function changeCurrent($domainId)
    {
        // 指定されたドメインが存在しない場合、権限がない場合は変更しない
        if (!$domainId
            || empty($domainId)
            || !preg_match('/^[0-9]+$/', $domainId)
            || !$this->hasDomain($domainId))
        {
            return false;
        }

        // 現在のドメインを変更
        $this->_current = $domainId;

        return true;
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
            'ip'        => $this->ip(),
            'user'      => $this->user(),
            'domains'   => $this->domains(),
            'current'   => $this->current(),
            'roleKname' => $this->roleKname(),
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

        if (array_key_exists('ip', $array) && !is_null($array['ip'])) {
            $this->_ip = $array['ip'];
        }
        if (array_key_exists('user', $array) && !is_null($array['user'])) {
            $this->_user = $array['user'];
        }
        if (array_key_exists('domains', $array) && !is_null($array['domains'])) {
            $this->_domains = $array['domains'];
        }
        if (array_key_exists('current', $array) && !is_null($array['current'])) {
            $this->_current = $array['current'];
        }
        if (array_key_exists('roleKname', $array) && !is_null($array['roleKname'])) {
            $this->_roleKname = $array['roleKname'];
        }
    }

}