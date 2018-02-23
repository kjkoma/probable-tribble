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
namespace App\Controller\Api;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * API Controller
 *
 * Add your api methods in the class below, your controllers will inherit them.
 *
 * - - -
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class ApiController extends Controller
{
    /**
     * data object & key
     */
    protected $_datas = array();
    const PAYLOAD_DATA   = 'data';

    /**
     * error object & key
     */
    protected $_errors = array();
    const PAYLOAD_ERROR        = 'error';
    const PAYLOAD_ERROR_STATUS = 'code';
    const PAYLOAD_ERROR_CODE   = 'app_code';
    const PAYLOAD_ERROR_MSG    = 'message';

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('AppSession');
        $this->loadComponent('AppLog');
        $this->loadComponent('AppUser');
        $this->loadComponent('AppGlobal');
        $this->loadComponent('AppError');

        $this->loadComponent('Auth', [
            'storage'      => 'Memory',
            'authenticate' => [
                'ADmad/JwtAuth.Jwt' => [
                    'userModel' => 'SUsers',
                    'fields'    => [
                        'username' => 'token'
                    ],

                    'parameter' => 'token',

                    // Boolean indicating whether the "sub" claim of JWT payload
                    // should be used to query the Users model and get user info.
                    // If set to `false` JWT's payload is directly returned.
                    'queryDatasource' => false,
                ]
            ],

            'unauthorizedRedirect' => false,
            'checkAuthIn' => 'Controller.initialize',

            // If you don't have a login action in your application set
            // 'loginAction' to false to prevent getting a MissingRouteException.
            'loginAction' => false
        ]);
    }

    /**
     * Before filter callback.
     *
     * @param \Cake\Event\Event $event The beforeFilter event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeFilter(Event $event)
    {
        $this->RequestHandler->renderAs($this, 'json');
        $this->response->cors($this->request)
            ->allowOrigin(['*'])
            ->allowMethods(['GET', 'POST', 'PUT', 'DELETE'])
            ->allowHeaders(['X-CSRF-Token'])
            ->allowCredentials()
            ->exposeHeaders(['Link'])
            ->maxAge(500)
            ->build();

        // JWT関連処理
        $jwt = $this->Auth->user();
        $this->AppUser->createByJWT($jwt);
        $clientIp = (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
        if ($jwt['ip'] != $clientIp) {
            throw new \Cake\Network\Exception\UnauthorizedException(__('Your Request is unauthorized.'));
        }
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        $this->response = $this->response->withCharset('UTF-8');
        $this->response = $this->response->withCharset('UTF-8');
        $this->set(self::PAYLOAD_DATA , $this->_datas);
        $this->set(self::PAYLOAD_ERROR, $this->_errors);
        $this->set('_serialize', [self::PAYLOAD_DATA, self::PAYLOAD_ERROR]);
    }

    /**
     * After filter callback.
     *
     * @param \Cake\Event\Event $event The afterFilter event.
     * @return \Cake\Network\Response|null|void
     */
    public function afterFilter(Event $event)
    {
        // write something ...
    }

    /**
     * 本クラスのデータオブジェクトに引数で渡されたデータを追加する
     *
     * @param mixed $data データオブジェクト
     * @return void
     */
    public function setData($data)
    {
        $this->_datas = $data;
        if (!isset($this->_errors[self::PAYLOAD_ERROR_STATUS])
                || empty($this->_errors[self::PAYLOAD_ERROR_STATUS])) {
            $this->_errors[self::PAYLOAD_ERROR_STATUS] = 200;
        }
    }

    /**
     * レスポンスメッセージを設定する
     *
     * @param boolean $result true/false
     * @param string $msg メッセージ
     * @param mixed $data レスポンスデータ
     * @return void
     */
    public function setResponse($result, $msg, $data)
    {
        $data = (object)[
            'result'  => ($result) ? 'true' : 'false',
            'message' => __($msg),
            'param'   => $data,
        ];
        $this->setData($data);
    }

    /**
     * レスポンスメッセージ（エラー）を設定する
     *
     * @param string $message メッセージ
     * @param mixed $errors エラーデータ
     * @return void
     */
    public function setResponseError($message, $errors)
    {
        $this->log('=S= APP_ERROR : ' . __($message));
        $this->log('=== CONTROLLER : ' . $this->name . ' : ' . $this->request->action);
        $this->log($errors);
        $this->log('=E= APP_ERROR');

        $data = (object)[
            'result'  => 'false',
            'message' => __($message),
            'errors'  => $errors,
        ];
        $this->setData($data);
    }

    /**
     * 本クラスのエラーオブジェクトに引数で渡されたエラーを追加する
     *
     * @param string  $message エラーメッセージ
     * @param string  $code エラーコード(default: UNEXPECTED_ERROR）
     * @param mixed   $keyInfo ログ出力する情報
     * @param integer $status HTTPステータス
     * @return void
     */
    public function setError($message, $code = 'UNEXPECTED_ERROR', $keyInfo = '', $status = 400)
    {
        $this->_errors[self::PAYLOAD_ERROR_MSG]    = __($message);
        $this->_errors[self::PAYLOAD_ERROR_CODE]   = $code;
        $this->_errors[self::PAYLOAD_ERROR_STATUS] = $status;

        $this->log('=S= ERROR : ' . $this->name . ' : ' . $this->request->action . ' : ' . $code . ' : ' . __($message));
        $this->log($keyInfo);
        $this->log('=E= ERROR');
    }

    /**
     * モデル用のコンポーネントをロードする
     *
     * - - -
     * @param string $modelComponent モデル用コンポーネントの名前
     */
    public function _loadComponent($modelComponent)
    {
        $this->loadComponent($modelComponent, [
            'appUser' => $this->AppUser,
        ]);
    }

    /**
     * リクエストデータをチェックする
     *
     * - - -
     * @param string|array $keys リクエストデータよりデータを取得するキー
     * @param array  $allowActions 許可するアクション（['post', 'get']など）/ 未指定：全許可
     * @param mixed リクエストデータ/不正時はfalseを返す
     */
    protected function validateParameter($keys, $allowActions = null)
    {
        if (!is_null($allowActions)) {
            $match = false;
            foreach($allowActions as $action) {
                $match = $this->request->is($action);
                if ($match) break;
            }
            if (!$match) {
                $this->setError('指定されたHTTPメソッドには対応していません。', 'UNSUPPORTED_HTTP_METHOD', $data);
                return false;
            }
        }

        $data = $this->request->getData();
        $keys = is_array($keys) ? $keys : [$keys];
        foreach($keys as $key) {
            if (!$data || !array_key_exists($key, $data)) {
                $this->setError('パラメータが不足しています。', 'MISSING_PARAMETERS', $data);
                return false;
            }
        }

        return $data;
    }
}
