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

use Cake\Controller\Controller;
use Cake\Event\Event;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /** @var array $_mysession コントローラー固有セッションオブジェクト（セッション） */
    protected $_mysession = array();

    /** @var array $_errors エラーオブジェクト（セッション） */
    protected $_errors = array();

    /** @var array $_referer リファラオブジェクト（セッション） */
    protected $_referer = array();

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * - - -
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Csrf');
        $this->loadComponent('Cookie');
        $this->loadComponent('AppSession');
        $this->loadComponent('AppLog');
        $this->loadComponent('AppRememberMe');
        $this->loadComponent('AppUser');
        $this->loadComponent('AppGlobal');
        $this->loadComponent('AppError');

        // Authentication settings
        $this->loadComponent('Auth', [
            'loginAction'  => [
                'prefix' => false,
                'controller' => 'Auth',
                'action'     => 'login'
            ],
            'flash' => ['key' => 'auth'],
            'authError' => '指定された認証情報で認証することができません。認証情報を確認してください。',
            'authenticate' => [
                'Form' => [
                    'userModel' => 'Susers',
                    'fields'    => [ 'username' => 'email', 'password' => 'password' ],
                    'finder'    => 'valid'
                ]
            ],
            'storage' => 'Session',
            'loginRedirect'  => [
                'prefix' => false,
                'controller' => 'Home',
                'action'     => 'home'
            ],
            'logoutRedirect' => [
                'prefix' => false,
                'controller' => 'Index',
                'action'     => 'index'
            ]
        ]);
    }

    /**
     * Before filter callback.
     *
     * - - -
     * @param \Cake\Event\Event $event The beforeFilter event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeFilter(Event $event)
    {
        // Auth Component Bug対応（未認証時に/logoutをgetした後のログインができない問題へ対応）
        if ($this->request->getSession()->check('Auth.redirect')) {
            if ($this->request->getSession()->read('Auth.redirect') == '/logout') {
                $this->request->getSession()->delete('Auth.redirect');
            }
        }

        // 未ログインの場合、CookieにRemember Me情報があればセッションを復元する
        if (!$this->Auth->user()) {
            $this->AppRememberMe->autoLogin();
        }

        // 認証ユーザー情報／グローバルデータの復元
        $this->AppUser->fromArray($this->AppSession->appUser());
        $this->AppGlobal->fromArray($this->AppSession->appGlobal());

        // ログイン時とIPアドレスが異なる場合はエラーとする
        if ($this->Auth->user()) {
            if ($this->AppUser->ip() != $_SERVER['REMOTE_ADDR']) {
                //throw new \Cake\Network\Exception\UnauthorizedException(__('Your Request is unauthorized.'));
            }
        }

        // セッション／リファラ情報の復元
        $this->_mysession = $this->AppSession->getControllerSession();
        $this->_referer   = [
                'referer' => $this->referer(true),
            ];
    }

    /**
     * Before render callback.
     *
     * - - -
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        if ($this->AppSession) { // Debug & RouteException対応の条件文（本来この条件は不要）
            $this->AppSession->storeController($this->_mysession);
            // JWTの更新
            $this->AppGlobal->fromArray($this->AppSession->appGlobal());
            $this->AppGlobal->refreshJWT($this->AppUser);
            $this->AppSession->refreshAppGlobal($this->AppGlobal);
        }
        $this->set('referer', $this->_referer);
        $this->set('errors' , $this->_errors);

        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }

    /**
     * After filter callback.
     *
     * - - -
     * @param \Cake\Event\Event $event The afterFilter event.
     * @return \Cake\Network\Response|null|void
     */
    public function afterFilter(Event $event)
    {
        // something ...
    }

    /**
     * 本クラスのエラーオブジェクトに引数で渡されたエラーを追加する
     *
     * - - -
     * @param array $error モデル／フォームのエラーオブジェクト
     * @return void
     */
    public function setError($error)
    {
        array_push($this->_errors, $error);
    }

    /**
     * モデル用のコンポーネントをロードする
     *
     * - - -
     * @param string $modelComponent モデル用コンポーネントの名前
     */
    protected function _loadModelComponent($modelComponent)
    {
        $this->loadComponent($modelComponent, [
            'appUser' => $this->AppUser,
        ]);
    }

}
