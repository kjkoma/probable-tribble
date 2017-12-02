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

use Cake\ORM\TableRegistry;

/**
 * 名称（Snames）へのアクセス用コンポーネント
 * 
 */
class SysModelSnamesComponent extends AppComponent
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'Snames';
        parent::initialize($config);
    }

    /**
     * 指定したキーの一覧を取得する
     *  
     * - - -
     * @param string $key nkeyの値
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 名称ー一覧
     */
    public function byKey($key, $toArray = false)
    {
        $query = $this->modelTable->find('values', ['nkey' => $key]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 指定したキーとidに対応する値を取得する
     *  
     * - - -
     * @param string $key nkeyの値
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return \App\Model\Entity\Sname 名称の値
     */
    public function byKeyId($key, $id)
    {
        $query = $this->modelTable->find('value', ['nkey' => $key, 'nid' => $id]);

        return $query->first();
    }

}