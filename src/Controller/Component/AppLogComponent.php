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

use Cake\Log\Log;

/**
 * ログを出力する
 *  
 */
class AppLogComponent extends AppComponent
{
    /**
     * 認証時のログを出力する
     *  
     * - - -
     * @param \Cake\Network\Request リクエスト
     * @param string 認証結果（"success" or "failed")
     * @return void
     */
    public function auth($request, $result)
    {
        $msg = "[auth] "
               . "URI:["     . env('REQUEST_URI')        . "] "
               . "REFERER:[" . env('HTTP_REFERER')       . "] "
               . "AGENT:["   . env('HTTP_USER_AGENT')    . "] "
               . "REMOTE:["  . env('REMOTE_ADDR')        . "] "
               . "EMAIL:["   . $request->data['email']   . "] "
               . "RESULT:["  . $result                   . "]";

        $this->writeAuth($msg);
    }

    /**
     * ログイン時のログを出力する
     *  
     * - - -
     * @param \App\Model\Table\SystemUsersTable システムユーザーオブジェクト
     * @return void
     */
    public function login($user)
    {
        $msg = "[login] "
               . "URI:["     . env('REQUEST_URI')       . "] "
               . "REFERER:[" . env('HTTP_REFERER')      . "] "
               . "AGENT:["   . env('HTTP_USER_AGENT')   . "] "
               . "REMOTE:["  . env('REMOTE_ADDR')       . "] "
               . "EMAIL:["   . $user['email']           . "] "
               . "USER:["    . $user['id']              . "]";

        $this->writeAuth($msg);
    }

    /**
     * ログアウト時のログを出力する
     *  
     * - - -
     * @param \App\Model\Table\SystemUsersTable システムユーザーオブジェクト
     * @return void
     */
    public function logout($user)
    {
        if (!$user || is_null($user) || count($user) === 0) {
            return ;
        }

        $msg = "[logout] "
               . "URI:["     . env('REQUEST_URI')       . "] "
               . "REFERER:[" . env('HTTP_REFERER')      . "] "
               . "AGENT:["   . env('HTTP_USER_AGENT')   . "] "
               . "REMOTE:["  . env('REMOTE_ADDR')       . "] "
               . "USER:["    . $user['id']              . "]";

        $this->writeAuth($msg);
    }

    /**
     * (private)ログを出力する
     *  
     * - - -
     * @param string 出力文字列
     * @return void
     */
    private function writeAuth($str)
    {
        Log::debug($str, ['scope' => ['auth']]);
    }

    /**
     * デバッグログを出力する
     *  
     * - - -
     * @param string $msg 出力メッセージ
     * @param string $class クラス名
     * @param string $func  関数名
     * @return void
     */
    public function debug($msg, $class, $func)
    {
        $msg = "[debug] "
               . "URI:["     . env('REQUEST_URI')       . "] "
               . "REFERER:[" . env('HTTP_REFERER')      . "] "
               . "AGENT:["   . env('HTTP_USER_AGENT')   . "] "
               . "REMOTE:["  . env('REMOTE_ADDR')       . "] "
               . "CLASS:["   . $class                   . "] "
               . "FUNCTION:[". $func                    . "] "
               . "MESSAGE:[" . $msg                     . "]";

        $this->writeDebug($msg);
    }

    /**
     * (private)デバッグログを出力する
     *  
     * - - -
     * @param string 出力文字列
     * @return void
     */
    private function writeDebug($str)
    {
        Log::debug($str);
    }

}