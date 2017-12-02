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
 * ドメインアプリケーション（DomainApps）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること（システム管理時を除く）
 * 
 */
class ModelDomainAppsComponent extends AppModelComponent
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
        $config['modelName'] = 'DomainApps';
        parent::initialize($config);
    }

    /**
     * 特定のドメインアプリケーション一覧を取得する
     *  
     * - - -
     * @param integer $domain_id ドメインID
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array アプリケーション一覧
     */
    public function findByDomainId($domain_id, $toArray = false)
    {
        $query = $this->modelTable->find('sorted', $this->_allow())
            ->where(['domain_id' => $domain_id]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 指定のドメインID／アプリケーションIDのデータを取得する
     *  
     * - - -
     * @param integer $domain_id ドメインID
     * @param integer $sapp_id アプリケーションID
     * @return \App\Model\Entity\DomainApps ドメインアプリケーションデータ
     */
    public function findByUniqueKey($domain_id, $sapp_id)
    {
        $query = $this->modelTable->find('all', $this->_allow())
            ->where(['domain_id' => $domain_id, 'sapp_id' => $sapp_id]);

        return $query->first();
    }

    /**
     * 指定したドメインIDに対して１つ以上のアプリケーションデータを追加する
     *  
     * - - -
     * @param integer $domain_id ドメインID
     * @param array $domainApps 複数のドメインアプリケーションデータ（リクエストデータ）
     * @param integer $user_id ユーザーID
     * @return array 保存結果（result:true/false, errors:エラー内容, data: 保存データ）
     */
    public function addByDomainId($domain_id, $domainApps, $user_id)
    {
        $domainApps = is_array($domainApps) ? $domainApps : [ $domainApps ];

        $result = ['result' => true];
        foreach($domainApps as $sapp_id) {
            $result = $this->add(['domain_id' => $domain_id, 'sapp_id' => $sapp_id], $user_id);
            if (!$result['result']) {
                return $result;
            }
        }

        return $result;
    }

    /**
     * 指定されたドメインのデータを削除する
     *  
     * - - -
     * @param integer $domain_id ドメインID
     * @return boolean true:成功|false:失敗
     */
    public function deleteByDomainId($domain_id)
    {
        $apps = $this->findByDomainId($domain_id);

        foreach($apps as $app) {
            $result = $this->delete($app['id'], $this->_allow());
            if (!$result['result']) {
                return $result;
            }
        }

        return $this->_result(true, $apps);
    }
}