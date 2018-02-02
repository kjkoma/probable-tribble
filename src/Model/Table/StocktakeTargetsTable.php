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

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StocktakeTargets Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\StocktakesTable|\Cake\ORM\Association\BelongsTo $Stocktakes
 * @property \App\Model\Table\AssetsTable|\Cake\ORM\Association\BelongsTo $Assets
 *
 * @method \App\Model\Entity\StocktakeTarget get($primaryKey, $options = [])
 * @method \App\Model\Entity\StocktakeTarget newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StocktakeTarget[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StocktakeTarget|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StocktakeTarget patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StocktakeTarget[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StocktakeTarget findOrCreate($search, callable $callback = null, $options = [])
 */
class StocktakeTargetsTable extends AppTable
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

        $this->setTable('stocktake_targets');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Stocktakes', [
            'foreignKey' => 'stocktake_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Assets', [
            'foreignKey' => 'asset_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Stocks', [
            'foreignKey' => 'asset_id',
            'bindingKey' => 'asset_id',
            'conditions' => ['Stocks.domain_id = StocktakeTargets.domain_id']
        ]);
        $this->hasOne('StocktakeDetails', [
            'foreignKey' => 'asset_id',
            'bindingKey' => 'asset_id',
            'conditions' => [
                'StocktakeDetails.stocktake_id = StocktakeTargets.stocktake_id',
                'StocktakeDetails.domain_id = StocktakeTargets.domain_id'
            ]
        ]);
        $this->belongsTo('StocktakeTargetAssetTypeName', [
            'className'  => 'Snames',
            'foreignKey' => 'asset_type',
            'bindingKey' => 'nid',
            'conditions' => ['StocktakeTargetAssetTypeName.nkey' => 'ASSET_TYPE']
        ]);
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
            ->scalar('asset_type')
            ->requirePresence('asset_type', 'create')
            ->notEmpty('asset_type');

        $validator
            ->integer('stock_count')
            ->requirePresence('stock_count', 'create')
            ->notEmpty('stock_count');

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
        $rules->add($rules->existsIn(['stocktake_id'], 'Stocktakes'));
        $rules->add($rules->existsIn(['asset_id'], 'Assets'));

        return $rules;
    }
}
