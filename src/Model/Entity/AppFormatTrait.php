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
        return $dt->i18nFormat('yyyy/MM/dd HH:mm:ss');
    }

    /**
     * 日を標準フォーマットでフォーマットする（プロパティ用）
     *  
     * - - -
     * @param string property_name プロパティ名
     * @return string フォーマット後の文字列
     */
    private function __dateFormatProperty($property_name)
    {
        if (!isset($this->_properties[$property_name])) {
            return null;
        }

        return $this->__dateFormat($this->_properties[$property_name]);
    }

    /**
     * 日時を標準フォーマットでフォーマットする（プロパティ用）
     *  
     * - - -
     * @param string property_name プロパティ名
     * @return string フォーマット後の文字列
     */
    private function __datetimeFormatProperty($property_name)
    {
        if (!isset($this->_properties[$property_name])) {
            return null;
        }

        return $this->__datetimeFormat($this->_properties[$property_name]);
    }

    /**
     * データ作成日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getCreatedAt()
    {
        return $this->__datetimeFormatProperty('created_at');
    }

    /**
     * データ更新日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getModifiedAt()
    {
        return $this->__datetimeFormatProperty('modified_at');
    }

    /**
     * 開始日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getStartDate()
    {
        return $this->__dateFormatProperty('start_date');
    }

    /**
     * 終了日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getEndDate()
    {
        return $this->__dateFormatProperty('end_date');
    }

    /**
     * 販売開始日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getSalesStart()
    {
        return $this->__dateFormatProperty('sales_start');
    }

    /**
     * 販売終了日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getSalesEnd()
    {
        return $this->__dateFormatProperty('sales_end');
    }

    /**
     * 製造日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getMakedDate()
    {
        return $this->__dateFormatProperty('maked_date');
    }

    /**
     * 予定日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getPlanDate()
    {
        return $this->__dateFormatProperty('plan_date');
    }

    /**
     * 入庫日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getInstockDate()
    {
        return $this->__dateFormatProperty('instock_date');
    }

    /**
     * 出庫日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getPickingDate()
    {
        return $this->__dateFormatProperty('picking_date');
    }

    /**
     * サポート期限日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getSupportLimitDate()
    {
        return $this->__dateFormatProperty('support_limit_date');
    }

    /**
     * 依頼日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getReqDate()
    {
        return $this->__dateFormatProperty('req_date');
    }

    /**
     * 到着希望日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getArvDate()
    {
        return $this->__dateFormatProperty('arv_date');
    }

    /**
     * 受付日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getRcvDate()
    {
        return $this->__dateFormatProperty('rcv_date');
    }

    /**
     * 初回入庫日を取得する
     *
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getFirstInstockDate()
    {
        return $this->__dateFormatProperty('first_instock_date');
    }

    /**
     * 初回出荷日（計上日）を取得する
     *
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getAccountDate()
    {
        return $this->__dateFormatProperty('account_date');
    }

    /**
     * 棚卸日を取得する
     *
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getStocktakeDate()
    {
        return $this->__dateFormatProperty('stocktake_date');
    }

    /**
     * 在庫締日を取得する
     *
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getStockDeadlineDate()
    {
        return $this->__dateFormatProperty('stock_deadline_date');
    }

    /**
     * 作業日(実施日)を取得する
     *
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getWorkDate()
    {
        return $this->__dateFormatProperty('work_date');
    }

    /**
     * 購入日を取得する
     *
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getPurchaseDate()
    {
        return $this->__dateFormatProperty('purchase_date');
    }

    /**
     * 変更日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getChangeAt()
    {
        return $this->__datetimeFormatProperty('change_at');
    }

    /**
     * 廃棄日を取得する
     *  
     * - - -
     * @return string フォーマット後の文字列
     */
    public function _getAbrogateDate()
    {
        return $this->__dateFormatProperty('abrogate_date');
    }
}
