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
 * システムユーザードメイン（SuserDomains）へのアクセス用コンポーネント
 *  
 */
class SysModelSuserDomainsComponent extends AppComponent
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
        $config['modelName'] = 'SuserDomains';
        parent::initialize($config);
    }

    /**
     * 特定のシステムユーザードメイン一覧を取得する
     *  
     * - - -
     * @param integer $suserId システムユーザーID
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array ドメイン一覧
     */
    public function findBySuserId($suserId, $toArray = false)
    {
        $query = $this->modelTable->find('sorted')
            ->where(['suser_id' => $suserId]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 指定のシステムユーザーID／ドメインIDのデータを取得する
     *  
     * - - -
     * @param integer $suserId システムユーザーID
     * @param integer $domainId ドメインID
     * @return \App\Model\Entity\SuserDomain システムユーザードメインデータ
     */
    public function findByUniqueKey($suserId, $domainId)
    {
        $query = $this->modelTable->find('all')
            ->where(['suser_id' => $suserId, 'domain_id' => $domainId]);

        return $query->first();
    }

    /**
     * 指定したシステムユーザーIDに対して１つ以上のドメインデータを追加する
     *  
     * - - -
     * @param integer $suserId システムユーザーID
     * @param array $suserDomain 複数のシステムユーザードメインデータ（リクエストデータ）
     * @return array 保存結果（result:true/false, errors:エラー内容, data: 保存データ）
     */
    public function addBySuserId($suserId, $suserDomain)
    {
        $suserDomain = is_array($suserDomain) ? $suserDomain : [ $suserDomain ];

        $result = ['result' => true];
        foreach($suserDomain as $domain) {
            if (!array_key_exists('domain_id', $domain)) {
                continue;
            }
            $result = $this->add([
                'suser_id'       => $suserId,
                'domain_id'      => $domain['domain_id'],
                'srole_id'       => $domain['srole_id'],
                'default_domain' => (array_key_exists('default_domain', $domain)) ? $domain['default_domain'] : '0',
            ]);
            if (!$result['result']) {
                return $result;
            }
        }

        return $result;
    }

    /**
     * 指定されたシステムユーザーIDのデータを削除する
     *  
     * - - -
     * @param integer $suserId システムユーザーID
     * @return boolean true:成功|false:失敗
     */
    public function deleteBySuserId($suserId)
    {
        $domains = $this->findBySuserId($suserId);

        foreach($domains as $domain) {
            $result = $this->delete($domain['id']);
            if (!$result['result']) {
                return $result;
            }
        }

        return $this->_result(true, $domains);
    }
}