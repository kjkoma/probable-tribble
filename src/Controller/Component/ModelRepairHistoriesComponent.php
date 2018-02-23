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

use Cake\Core\Configure;

/**
 * 修理履歴（RepairHistories）へのアクセス用コンポーネント
 *  
 * ※データ取得時は親コンポーネントのtableメソッドを利用し、ドメイン指定でデータ取得すること
 * 
 */
class ModelRepairHistoriesComponent extends AppModelComponent
{
    /** @var array $components 利用コンポーネント */
    // public $components = [''];

    /**
     * 本クラスの初期化処理を行う
     *  
     * - - -
     * @param array $config コンフィグ
     * @return void
     */
    public function initialize(array $config)
    {
        $config['modelName'] = 'RepairHistories';
        parent::initialize($config);
    }

    /**
     * 修理IDより修理履歴一覧を取得する
     *  
     * - - -
     * @param string repairId 修理ID
     * @param boolean $toArray true:配列で返す|false:ResultSetで返す（default）
     * @return array 修理履歴一覧（ResultSet or Array）
     */
    public function histories($repairId, $toArray = false)
    {
        $query = $this->modelTable->find('valid')
            ->where([
                'repair_id' => $repairId
            ])
            ->contain(['RepairHistorySusers' => function($q) {
                return $q->select(['id', 'kname']);
            }])
            ->order([
                'RepairHistories.history_date' => 'DESC',
                'RepairHistories.id'           => 'DESC'
            ]);

        return ($toArray) ? $query->toArray() : $query->all();
    }

    /**
     * 修理履歴を追加する
     *  
     * - - -
     * @param string $entry 入力データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function addNew($entry)
    {
        $entry['domain_id']        = $this->current();
        $entry['history_suser_id'] = $this->user();

        return parent::add($entry);
    }

    /**
     * 修理履歴を編集する
     *  
     * - - -
     * @param string $entry 入力データ
     * @return array {result: true/false, data: 結果データ, errors: エラーデータ}
     */
    public function edit($entry)
    {
        $history = parent::get($entry['id']);
        if (!$history || count($history) == 0) {
            return $this->_invalid('修理履歴情報が存在しない為、更新できませんでした。'. ['method' => __METHOD__, 'entry' => $entry]);
        }

        $history['history_suser_id'] = $this->user();

        return parent::save($history->toArray());
    }
}
