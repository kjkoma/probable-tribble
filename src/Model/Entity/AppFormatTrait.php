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

use Cake\I18n\Time;

/**
 * フォーマット後データの取得用トレイト
 *  
 * 本トレイトはEntityの拡張メソッドを共通で提供する為に利用します。
 */
trait AppFormatTrait
{
    /**
     * 日を標準フォーマットでフォーマットする
     *  
     * - - -
     * @param string $datetime 日文字列
     * @return string フォーマット後の文字列
     */
    private function __dateFormat($date)
    {
        if (!date_parse($date)) {
            return $date;
        }

        $dt = Time::parse($date);
        return $dt->i18nFormat('yyyy/MM/dd');
    }

    /**
     * 日時を標準フォーマットでフォーマットする
     *  
     * - - -
     * @param string $datetime 日時文字列
     * @return string フォーマット後の文字列
     */
    private function __datetimeFormat($datetime)
    {
        if (!date_parse($datetime)) {
            return $datetime;
        }

        $dt = Time::parse($datetime);
        return $dt->i18nFormat('yyyy-MM-dd HH:mm:ss');
    }

    /**
     * データ作成日を取得する
     *  
     * - - -
     * @param string $value    文字列
     * @return string トリム後の文字列
     */
    public function _getCreated()
    {
        if (!isset($this->_properties['created'])) {
            return null;
        }

        return $this->__datetimeFormat($this->_properties['created']);
    }

    /**
     * データ更新日を取得する
     *  
     * - - -
     * @param string $value    文字列
     * @return string トリム後の文字列
     */
    public function _getModified()
    {
        if (!isset($this->_properties['modified'])) {
            return null;
        }

        return $this->__datetimeFormat($this->_properties['modified']);
    }

    /**
     * 開始日を取得する
     *  
     * - - -
     * @param string $value    文字列
     * @return string トリム後の文字列
     */
    public function _getStartDate()
    {
        if (!isset($this->_properties['start_date'])) {
            return null;
        }

        return $this->__dateFormat($this->_properties['start_date']);
    }

    /**
     * 終了日を取得する
     *  
     * - - -
     * @param string $value    文字列
     * @return string トリム後の文字列
     */
    public function _getEndDate()
    {
        if (!isset($this->_properties['end_date'])) {
            return null;
        }

        return $this->__dateFormat($this->_properties['end_date']);
    }

    /**
     * 販売開始日を取得する
     *  
     * - - -
     * @param string $value    文字列
     * @return string トリム後の文字列
     */
    public function _getSalesStart()
    {
        if (!isset($this->_properties['sales_start'])) {
            return null;
        }

        return $this->__dateFormat($this->_properties['sales_start']);
    }

    /**
     * 販売終了日を取得する
     *  
     * - - -
     * @param string $value    文字列
     * @return string トリム後の文字列
     */
    public function _getSalesEnd()
    {
        if (!isset($this->_properties['sales_end'])) {
            return null;
        }

        return $this->__dateFormat($this->_properties['sales_end']);
    }

    /**
     * 製造日日を取得する
     *  
     * - - -
     * @param string $value    文字列
     * @return string トリム後の文字列
     */
    public function _getMakedDate()
    {
        if (!isset($this->_properties['maked_date'])) {
            return null;
        }

        return $this->__dateFormat($this->_properties['maked_date']);
    }
}
