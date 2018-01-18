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
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * StockHistories Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\AssetsTable|\Cake\ORM\Association\BelongsTo $Assets
 * @property \App\Model\Table\InstocksTable|\Cake\ORM\Association\BelongsTo $Instocks
 * @property \App\Model\Table\PickingsTable|\Cake\ORM\Association\BelongsTo $Pickings
 * @property \App\Model\Table\StocktakesTable|\Cake\ORM\Association\BelongsTo $Stocktakes
 *
 * @method \App\Model\Entity\StockHistory get($primaryKey, $options = [])
 * @method \App\Model\Entity\StockHistory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StockHistory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StockHistory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StockHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StockHistory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StockHistory findOrCreate($search, callable $callback = null, $options = [])
 */
class StockHistoriesTable extends AppTable
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('stock_histories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Assets', [
            'foreignKey' => 'asset_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Instocks', [
            'foreignKey' => 'instock_id'
        ]);
        $this->belongsTo('Stocktakes', [
            'foreignKey' => 'stocktake_id'
        ]);

        $this->_sorted = [
            'StockHistories.asset_id' => 'ASC',
            'StockHistories.id'       => 'DESC'
        ];
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('history_type')
            ->requirePresence('history_type', 'create')
            ->notEmpty('history_type');

        $validator
            ->dateTime('change_at')
            ->requirePresence('change_at', 'create')
            ->notEmpty('change_at');

        $validator
            ->integer('stock_count_org')
            ->requirePresence('stock_count_org', 'create')
            ->notEmpty('stock_count_org');

        $validator
            ->integer('stock_count')
            ->requirePresence('stock_count', 'create')
            ->notEmpty('stock_count');

        $validator
            ->scalar('reason_kbn')
            ->requirePresence('reason_kbn', 'create')
            ->notEmpty('reason_kbn');

        $validator
            ->scalar('reason')
            ->allowEmpty('reason');

        $validator
            ->requirePresence('dsts', 'create')
            ->notEmpty('dsts');

        $validator
            ->integer('created_user')
            ->requirePresence('created_user', 'create')
            ->notEmpty('created_user');

        $validator
            ->integer('modified_user')
            ->requirePresence('modified_user', 'create')
            ->notEmpty('modified_user');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['domain_id'], 'Domains'));
        $rules->add($rules->existsIn(['asset_id'], 'Assets'));
        $rules->add($rules->existsIn(['instock_id'], 'Instocks'));
        $rules->add($rules->existsIn(['stocktake_id'], 'Stocktakes'));

        return $rules;
    }
}
