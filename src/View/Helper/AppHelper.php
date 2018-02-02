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

use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\View\Helper;
use Cake\Routing\Router;

/**
 * AppHelper
 * 
 * This helper is generic application helper for view.
 */
class AppHelper extends Helper
{
    /**
     * 初期化
     *
     * - - -
     * @param array $config コンフィグ
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
    }

    /**
     * デバッグログを出力する
     *  
     * - - -
     * @param mixed $output 出力内容
     */
    public function debug($output)
    {
        Log::debug($output);
    }

    /**
     * 情報ログを出力する
     *  
     * - - -
     * @param mixed $output 出力内容
     */
    public function info($output)
    {
        Log::info($output);
    }

    /**
     * 通知ログを出力する
     *  
     * - - -
     * @param mixed $output 出力内容
     */
    public function notice($output)
    {
        Log::notice($output);
    }

    /**
     * 警告ログを出力する
     *  
     * - - -
     * @param mixed $output 出力内容
     */
    public function warning($output)
    {
        Log::warning($output);
    }

    /**
     * エラーログを出力する
     *  
     * - - -
     * @param mixed $output 出力内容
     */
    public function error($output)
    {
        Log::error($output);
    }

    /**
     * 指定されたキーでコンフィグ（const.php）より値を取得する
     *
     * - - -
     * @param string $key コンフィグのキー
     * @return string|null 指定されたキーのコンフィグの値
     */
    public function conf($key)
    {
        if (!$key) {
            return null;
        }
        return Configure::read($key);
    }

    /**
     * 指定されたキーでセッションより値を取得する
     *
     * - - -
     * @param string $key セッションのキー
     * @return string|null 指定されたキーのセッションの値
     */
    public function session($key)
    {
        if (!$key) {
            return null;
        }
        return $this->request->getSession()->read($key);
    }

    /**
     * コンフィグ（const.php）の指定されたキーの値でセッションより値を取得する
     *
     * - - -
     * @param string $confKey コンフィグのキー
     * @return string|null 指定されたコンフィグキーの値をキーとしたセッションの値
     */
    public function sessionByConfig($confKey)
    {
        if (!$confKey) {
            return null;
        }
        $key = $this->conf($confKey);
        return $this->session($key);
    }

    /**
     * 配列より指定されたキーの値を取得する（存在しない場合、nullを返す）
     *
     * - - -
     * @param array $array 配列
     * @param array $keys 配列キー（階層の場合、配列で指定 - [key1, key2, ...]）
     * @return mixed|null 指定されたキーの値（存在しない場合はnull）
     */
    public function nArray($array, $keys)
    {
        if (!$array || count($array) === 0 || !$keys) {
            return null;
        }

        $t = gettype($array);
        if ($t !== "object" && $t !== "array") {
            return null;
        }

        $t = gettype($keys);
        if ($t === "array") {
            $tmp = $array;
            foreach ($keys as $key) {
                if (!array_key_exists($key, $tmp)) {
                    return null;
                }
                $tmp = $tmp[$key];
            }
            return $tmp;
        }

        return $array[$keys];
    }

    /**
     * 配列より指定されたキーの値を取得する（存在しない場合、ブランクを返す）
     *
     * - - -
     * @param array $array 配列
     * @param string|array $keys 配列キー（階層の場合、配列で指定 - [key1, key2, ...]）
     * @return mixed 指定されたキーの値（存在しない場合はブランク）
     */
    public function bArray($array, $keys)
    {
        $val = $this->nArray($array, $keys);
        return ($val === null) ? "" : $val;
    }

    /**
     * サイドメニューを出力する
     *
     * - - -
     * @param string $path URL
     * @param string $title タイトル名
     * @param string $icon アイコン名
     * @param string サイドメニュー要素（li要素)
     */
    public function menu($path, $title, $icon)
    {
        $requestPath = Router::url();
        $active = ($path == $requestPath) ? 'active' : '';

        $html = '<li class="' . $active . '"><a href="' . $path . '" title="' . $title . '">';
        $html = $html . '<i class="fa fa-lg fa-fw ' . $icon . '"></i> <span class="menu-item-parent">' . $title . '</span>';
        $html = $html . '</a></li>';

        return $html;
    }
}