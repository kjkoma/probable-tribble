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

/**
 * エラー判定などのエラー処理を行う為のコンポーネント
 *  
 */
class AppErrorComponent extends AppComponent
{
    /** @var array $_errors エラー情報 */
    private $_errors = [
        'has_error'   => false,
        'message'     => '',
        'message_key' => '',    // NOT_FOUND, INVALID_PARAMETERSなど任意のエラー識別キー
        'http_status' => '200',
        'errors'      => null,  // エラーデータ
    ];

    /**
     * エラーを設定する
     *  
     * - - -
     * @param $errors エラーデータ
     * @param $message エラーメッセージ
     * @param $messageKey エラーメッセージのキー
     * @param $httpStatus HTTPステータス
     */
    public function set($errors, $message = '', $messageKey = '', $httpStatus = '200') {
        $this->_errors = [
            'has_error'   => true,
            'message'     => $message,
            'message_key' => $messageKey,
            'http_status' => $httpStatus,
            'errors'      => $errors,
        ];
    }

    /**
     * エラーを設定する（AppComponentoのエンティティ保存結果より）
     *  
     * - - -
     * @param array $result エンティティ保存結果
     * @param boolean $overwrite true:エラーが既に存在しても上書きする/false:エラーが既に存在する場合は上書きしない
     * @param $message エラーメッセージ
     * @param $messageKey エラーメッセージのキー
     * @param $httpStatus HTTPステータス
     */
    public function result($result, $overwrite = true, $message = '', $messageKey = '', $httpStatus = '200') {
        if (!$overwrite || is_null($result)) return ;

        $this->_errors = [
            'has_error'   => !$result['result'],
            'message'     => $message,
            'message_key' => $messageKey,
            'http_status' => $httpStatus,
            'errors'      => (array_key_exists('errors', $result)) ? $result['errors'] : null,
        ];
    }

    /**
     * エラーをクリアする
     *  
     * - - -
     */
    public function clear() {
        $this->_errors = [
            'has_error'   => false,
            'message'     => '',
            'message_key' => '',
            'http_status' => '200',
            'errors'      => null,
        ];
    }

    /**
     * エラー有無を取得する
     *  
     * - - -
     * @return boolean true:エラーあり／false:エラーなし
     */
    public function has()
    {
        return  $this->_errors['has_error'];
    }

    /**
     * エラーメッセージを取得する
     *  
     * - - -
     * @return string エラーメッセージ
     */
    public function message()
    {
        return  $this->_errors['message'];
    }

    /**
     * エラーメッセージのキー名称を取得する
     *  
     * - - -
     * @return string エラーメッセージのキー名称
     */
    public function messageKey()
    {
        return  $this->_errors['message_key'];
    }

    /**
     * エラーメッセージのキー名称を取得する
     *  
     * - - -
     * @return string HTTPステータス
     */
    public function httpStatus()
    {
        return  $this->_errors['message_key'];
    }

    /**
     * エラーデータを取得する
     *  
     * - - -
     * @return mixed エラーデータ
     */
    public function errors()
    {
        return  $this->_errors['errors'];
    }

}