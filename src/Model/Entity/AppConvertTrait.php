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
namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Utility\Text;

/**
 * 変換ユーティリティトレイト
 *  
 */
trait AppConvertTrait
{
    /**
     * 文字列のトリムを行う
     *  
     * - - -
     * @param string $value 文字列
     * @return string トリム後の文字列
     */
    public function trim($value)
    {
        return mb_convert_kana(trim(mb_convert_kana($value, 's', 'UTF-8')), 'S', 'UTF-8');
    }

    /**
     * 全角を半角に変換する（全角スペースは変換しない）
     *  
     * - - -
     * @param string $value 文字列
     * @return string 変換後の文字列
     */
    public function zen2han($value)
    {
        return mb_convert_kana($value, 'aKV', 'UTF-8');
    }

    /**
     * 全角を半角に変換する（全角スペースも変換する）
     *  
     * - - -
     * @param string $value 文字列
     * @return string 変換後の文字列
     */
    public function zen2hanB($value)
    {
        return mb_convert_kana($value, 'asKV', 'UTF-8');
    }

    /**
     * 全角を半角に変換、および、トリムを行う
     * （全角スペースは変換しない）
     *  
     * - - -
     * @param string $value 文字列
     * @return string 変換後の文字列
     */
    public function zen2hanT($value)
    {
        return $this->trim($this->zen2han($value));
    }

    /**
     * 小文字を大文字に変換する
     *  
     * - - -
     * @param string $value 文字列
     * @return string 変換後の文字列
     */
    public function upper($value)
    {
        if (!$value) return $value;
        return mb_strtoupper($value);
    }

    /**
     * 大文字を子文字に変換する
     *  
     * - - -
     * @param string $value 文字列
     * @return string 変換後の文字列
     */
    public function lower($value)
    {
        if (!$value) return $value;
        return mb_strtolower($value);
    }

    /**
     * 指定文字数で文字列を切り取る
     *  
     * - - -
     * @param string $value 文字列
     * @param int $length 切り取る文字数
     * @return string 変換後の文字列
     */
    public function truncate($value, $length)
    {
        if (!$value) return $value;
        return Text::truncate($value, $length, ['ellipsis' => '']);
    }

    /**
     * パスワードの暗号化を行う
     *  
     * - - -
     * @param string $password パスワード文字列
     * @return string 暗号化後の文字列
     */
    public function encrypt($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }

    /**
     * 新たなトークンを生成する
     *
     * (Memo)hash('sha512', uniqid(rand(), true), false)の利用も検討
     * - - -
     * @return string トークン
     */
    public function createToken() {
        $token_length = Configure::read('WNote.DB.Susers.token_length');
        return bin2hex(openssl_random_pseudo_bytes($token_length));
    }
}
