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
 * Stocktakes Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\StocktakeSusersTable|\Cake\ORM\Association\BelongsTo $StocktakeSusers
 * @property \App\Model\Table\ConfirmSusersTable|\Cake\ORM\Association\BelongsTo $ConfirmSusers
 * @property \App\Model\Table\StockHistoriesTable|\Cake\ORM\Association\HasMany $StockHistories
 * @property \App\Model\Table\StocktakeDetailsTable|\Cake\ORM\Association\HasMany $StocktakeDetails
 *
 * @method \App\Model\Entity\Stocktake get($primaryKey, $options = [])
 * @method \App\Model\Entity\Stocktake newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Stocktake[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Stocktake|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Stocktake patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Stocktake[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Stocktake findOrCreate($search, callable $callback = null, $options = [])
 */
class StocktakesTable extends AppTable
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

        $this->setTable('stocktakes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('StocktakeSuserName', [
            'foreignKey' => 'stocktake_suser_id',
            'className'  => 'Susers',
        ]);
        $this->belongsTo('StocktakeConfirmSuserName', [
            'foreignKey' => 'confirm_suser_id',
            'className'  => 'Susers',
        ]);
        $this->hasMany('StockHistories', [
            'foreignKey' => 'stocktake_id'
        ]);
        $this->hasMany('StocktakeDetails', [
            'foreignKey' => 'stocktake_id',
            'dependent'  => true
        ]);
        $this->hasMany('StocktakeTargets', [
            'foreignKey' => 'stocktake_id',
            'dependent'  => true
        ]);
        $this->belongsTo('StocktakeStsName', [
            'className'  => 'Snames',
            'foreignKey' => 'stocktake_sts',
            'bindingKey' => 'nid',
            'conditions' => ['StocktakeStsName.nkey' => 'STOCKTAKE_STS']
        ]);

        $this->_sorted = [
            'Stocktakes.stocktake_date' => 'DESC',
            'Stocktakes.id'             => 'DESC'
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
            ->date('stocktake_date')
            ->requirePresence('stocktake_date', 'create')
            ->notEmpty('stocktake_date');

        $validator
            ->scalar('stocktake_sts')
            ->requirePresence('stocktake_sts', 'create')
            ->notEmpty('stocktake_sts');

        $validator
            ->date('start_date')
            ->allowEmpty('start_date');

        $validator
            ->date('end_date')
            ->allowEmpty('end_date');

        $validator
            ->date('stock_deadline_date')
            ->allowEmpty('stock_deadline_date');

        $validator
            ->scalar('remarks')
            ->allowEmpty('remarks');

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
        $rules->add($rules->existsIn(['stocktake_suser_id'], 'StocktakeSuserName'));
        $rules->add($rules->existsIn(['confirm_suser_id'], 'StocktakeConfirmSuserName'));

        return $rules;
    }
}
