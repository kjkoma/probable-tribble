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

use Cake\ORM\Entity;
use Cake\I18n\Date;

/**
 * アプリケーションのエンティティクラスの親クラス
 */
class AppEntity extends Entity
{
    /**
     * 作成日、更新日等の共通フォーマット変換用トレイト
     */
    use AppFormatTrait;

    /**
     * 文字列変換ユーティリティトレイト
     */
    use AppConvertTrait;


    /**
     * 現在日付で有効なデータかどうかを判定する
     *  
     * 「start_date」、「end_date」を持つモデルに対して現在日付で有効・無効を判定する
     *  
     * - - -
     * @return integer 0: 無効、1: 有効
     */
    protected function _getCurrent()
    {
        if (!isset($this->_properties['start_date']) ||
            !isset($this->_properties['end_date'])) {
            return 1;
        }

        $now   = new \DateTime();
        $start = new \DateTime($this->__dateFormat($this->_properties['start_date']));
        $end   = new \DateTime($this->__dateFormat($this->_properties['end_date']));

        if ($now >= $start && $now <= $end) {
            return 1;
        }

        return 0;
    }
}
