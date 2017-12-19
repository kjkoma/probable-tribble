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
 * 本アプリケーションのモデルアクセス用コンポーネントの親となるコンポーネント
 *  
 * 各モデルアクセス用コンポーネントの共通の処理を行うコンポーネント
 * ドメインを必ず指定するモデルは本コンポーネントを継承して作成する
 *  
 */
class AppModelComponent extends AppComponent
{
    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     *     [
     *         'appUser'     => 認証ユーザー情報（AppUserComponentのオブジェクト）
     *         'modelName'   => テーブルオブジェクトに登録するモデル名(string)
     *         'modelConfig' => テーブルオブジェクトの登録時のコンフィグ(array)
     *     ]
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($this->createConfig($config));
    }

    /**
     * 本クラスの再初期化を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function reset(array $config)
    {
        parent::reset($this->createConfig($config));
    }

    /**
     * 本クラスのコンフィグを作成する
     *  
     * - - -
     * @param array $config コンフィグ
     * @return array コンフィグ
     */
    public function createConfig(array $config)
    {
        $config = ($config) ? $config : [];
        $modelConfig = array_key_exists('modelConfig', $config) ? $config['modelConfig'] : [];
        $modelConfig['appUser'] = $config['appUser'];
        $config['modelConfig']  = $modelConfig;

        return $config;
    }

    /**
     * テーブルオブジェクトを返す
     *  
     * - - -
     * @param string $modelName テーブルオブジェクトに登録するモデル名
     * @param array $modelConfig テーブルオブジェクトの登録時のコンフィグ
     * @param boolean $init true:初期化時/false:再作成時
     * @return \Cake\ORM\Table テーブルオブジェクト
     */
    public function table($modelName, $modelConfig = [], $init = true)
    {
        if (!array_key_exists('appUser', $modelConfig)) {
            $modelConfig['appUser'] = $this->_appUser;
        }

        return parent::table($modelName, $modelConfig, $init);
    }

    /**
     * ドメイン指定の条件を解除するオプションを取得する
     *  
     * - - -
     * @return array ドメイン指定の条件を解除するオプション
     */
    protected function _allow()
    {
        return ['allowWithoutDomainId' => 'true'];
    }

}