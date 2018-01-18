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
 * NULLが更新時に指定された場合に空文字で更新する為のビヘイビア
 */
class BlankUpdateBehavior extends Behavior
{
    /**
     * 更新値がNULLの場合、空文字に変更する
     * 本ビヘイビアを適用する場合モデルのinitialize内の「parent::initialize($config);」の後に「$this->addBehavior('BlankUpdate');」と指定してください。
     */
    function beforeSave($event, $entity, $options) {
        foreach($this->_table->schema()->columns() as $key => $val) {
            if ($entity->get($val) === NULL) {
                $entity->set($val, '');
            }
        }
        return true;
    }
}
