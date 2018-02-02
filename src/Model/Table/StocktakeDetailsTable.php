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
 * StocktakeDetails Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\StocktakesTable|\Cake\ORM\Association\BelongsTo $Stocktakes
 * @property \App\Model\Table\ReadSusersTable|\Cake\ORM\Association\BelongsTo $ReadSusers
 *
 * @method \App\Model\Entity\StocktakeDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\StocktakeDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\StocktakeDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\StocktakeDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\StocktakeDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\StocktakeDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\StocktakeDetail findOrCreate($search, callable $callback = null, $options = [])
 */
class StocktakeDetailsTable extends AppTable
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

        $this->setTable('stocktake_details');
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
            'conditions' => ['Stocks.domain_id = StocktakeDetails.domain_id']
        ]);
        $this->belongsTo('Classifications', [
            'foreignKey' => 'classification_id'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id'
        ]);
        $this->belongsTo('ProductModels', [
            'foreignKey' => 'product_model_id'
        ]);
        $this->belongsTo('StocktakeTargets', [
            'foreignKey' => 'asset_id',
            'bindingKey' => 'asset_id',
            'conditions' => [
                'StocktakeTargets.stocktake_id = StocktakeDetails.stocktake_id',
                'StocktakeTargets.domain_id = StocktakeDetails.domain_id'
            ]
        ]);
        $this->belongsTo('StocktakeWorkSuserName', [
            'foreignKey' => 'work_suser_id',
            'className'  => 'Susers',
        ]);
        $this->belongsTo('StocktakeKbnName', [
            'className'  => 'Snames',
            'foreignKey' => 'stocktake_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['StocktakeKbnName.nkey' => 'STOCKTAKE_KBN']
        ]);
        $this->belongsTo('StocktakeDetailAssetTypeName', [
            'className'  => 'Snames',
            'foreignKey' => 'asset_type',
            'bindingKey' => 'nid',
            'conditions' => ['StocktakeDetailAssetTypeName.nkey' => 'ASSET_TYPE']
        ]);
        $this->belongsTo('StocktakeUnmatchKbnName', [
            'className'  => 'Snames',
            'foreignKey' => 'unmatch_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['StocktakeUnmatchKbnName.nkey' => 'ST_UNMATCH_KBN']
        ]);

        $this->_sorted = [
            'StocktakeDetails.stocktake_id' => 'DESC',
            'StocktakeDetails.asset_no'     => 'ASC',
            'StocktakeDetails.serial_no'    => 'ASC',
            'StocktakeDetails.id'           => 'DESC'
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
            ->scalar('asset_type')
            ->requirePresence('asset_type', 'create')
            ->notEmpty('asset_type');

        $validator
            ->scalar('serial_no')
            ->allowEmpty('serial_no');

        $validator
            ->scalar('asset_no')
            ->allowEmpty('asset_no');

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
        $rules->add($rules->existsIn(['work_suser_id'], 'StocktakeWorkSuserName'));

        return $rules;
    }
}
