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
 */
namespace App\Model\Validation;

use Cake\Validation\Validation;

/**
 * アプリケーション共有のバリデーションプロバイダー
 *  
 * * Emailフォーマットバリデーション
 * * 英数字、記号チェックバリデーション
 */
class AppValidation extends Validation
{
    /**
     * コンストラクタ
     *  
     * - - -
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Emailフォーマットをチェックするバリデーション
     *  
     * - - -
     * @param string $check チェック対象の文字列
     * @return boolean true: OK / false: NG
     */
    public static function emailFormat($check)
    {
        return (boolean)preg_match('/^[0-9a-z_\.\/\?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$/', $check);
    }

    /**
     * パスワードの組合せをチェックするバリデーション
     *  
     * 少なくとも英小文字、英大文字、数字がそれぞれ1つ以上含まれている場合にtrueを返す
     * - - -
     * @param string $check チェック対象の文字列
     * @return boolean true: OK / false: NG
     */
    public static function passwordCombination($check)
    {
        return (boolean)preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z]+$/', $check);
    }

    /**
     * 英数字と記号で構成されているかどうかをチェックするバリデーション
     *  
     * - - -
     * @param string $check チェック対象の文字列
     * @return boolean true: OK / false: NG
     */
    public static function alphaNumericWithSymbol($check)
    {
        return (boolean)preg_match('/^[a-zA-Z0-9\s\x21-\x2f\x3a-\x40\x5b-\x60\x7b-\x7e]+$/', $check);
    }

    /**
     * 識別IDをチェックするバリデーション
     *  
     * 英字（大文字）、数値、ハイフン（-）、アンダースコア（_）のみ許可
     *  
     * - - -
     * @param string $check チェック対象の文字列
     * @return boolean true: OK / false: NG
     */
    public static function keyIdFormat($check)
    {
        return (boolean)preg_match('/^[A-Z0-9_\-]+$/', $check);
    }

    /**
     * ドメインのキー名称をチェックするバリデーション
     *  
     * 英字（小文字）、数値、ハイフン（-）、アンダースコア（_）のみ許可
     *  
     * - - -
     * @param string $check チェック対象の文字列
     * @return boolean true: OK / false: NG
     */
    public static function keyNameFormat($check)
    {
        return (boolean)preg_match('/^[a-z0-9_\-]+$/', $check);
    }

    /**
     * URLのフォーマットをチェックするバリデーション
     *  
     * http://xxx.xxx.xxx/xxx or https://xxx.xxx.xxx/xxx
     *  
     * - - -
     * @param string $check チェック対象の文字列
     * @return boolean true: OK / false: NG
     */
    public static function urlFormat($check)
    {
        return (boolean)preg_match('/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?([A-Z0-9_-]+[\/])*([A-Z0-9_-]+[\/]?)?$/i', $check);
    }
}

