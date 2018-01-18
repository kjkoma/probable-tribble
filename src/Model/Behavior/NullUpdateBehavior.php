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
namespace App\Model\Behavior;

use Cake\ORM\Behavior;

/**
 * 空文字が更新時に指定された場合にNULL値で更新する為のビヘイビア
 */
class NullUpdateBehavior extends Behavior
{
    /**
     * 更新値が空文字の場合、NULL値に変更する
     */
    function beforeSave($event, $entity, $options) {
        foreach($this->_table->schema()->columns() as $key => $val) {
            if ($entity->get($val) === '') {
                $entity->set($val, NULL);
            }
        }
        return true;
    }
}
