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
 * Cpus Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\MakersTable|\Cake\ORM\Association\BelongsTo $Makers
 *
 * @method \App\Model\Entity\Cpus get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cpus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Cpus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cpus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cpus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cpus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cpus findOrCreate($search, callable $callback = null, $options = [])
 */
class CpusTable extends AppTable
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

        $this->setTable('cpus');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'maker_id'
        ]);

        $this->_sorted   = [
            'Cpus.maker_id' => 'ASC',
            'Cpus.kname' => 'ASC'
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
            ->scalar('kname')
            ->requirePresence('kname', 'create')
            ->notEmpty('kname');

        $validator
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('psts', 'create')
            ->notEmpty('psts');

        $validator
            ->date('sales_start')
            ->allowEmpty('sales_start');

        $validator
            ->date('sales_end')
            ->allowEmpty('sales_end');

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
        $rules->add($rules->existsIn(['maker_id'], 'Companies'));

        return $rules;
    }
}
