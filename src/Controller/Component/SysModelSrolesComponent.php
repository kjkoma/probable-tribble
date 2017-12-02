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
 * システムロール（Sroles）へのアクセス用コンポーネント
 * 
 */
class SysModelSrolesComponent extends AppComponent
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
        $config['modelName'] = 'Sroles';
        parent::initialize($config);
    }

    /**
     * システムロール一覧を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array システムロールー一覧
     */
    public function systems($toArray = false)
    {
        $query = $this->modelTable->find('systems');

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * ドメインロール一覧を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array システムロールー一覧
     */
    public function domains($toArray = false)
    {
        $query = $this->modelTable->find('domains');

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 一般ロール一覧を取得する
     *  
     * - - -
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array システムロールー一覧
     */
    public function publics($toArray = false)
    {
        $query = $this->modelTable->find('publics');

        return ($toArray) ? $query->toArray() : $query->all();
    }

}