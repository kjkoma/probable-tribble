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
        $this->belongsTo('Susers', [
            'foreignKey' => 'read_suser_id'
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
            ->scalar('serial_no')
            ->requirePresence('serial_no', 'create')
            ->notEmpty('serial_no');

        $validator
            ->scalar('asset_no')
            ->allowEmpty('asset_no');

        $validator
            ->date('read_date')
            ->allowEmpty('read_date');

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
        $rules->add($rules->existsIn(['read_suser_id'], 'Susers'));

        return $rules;
    }
}
