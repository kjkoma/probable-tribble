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
 * SuserDomains Model
 *
 * @property \App\Model\Table\SusersTable|\Cake\ORM\Association\BelongsTo $Susers
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\SrolesTable|\Cake\ORM\Association\BelongsTo $Sroles
 *
 * @method \App\Model\Entity\SuserDomain get($primaryKey, $options = [])
 * @method \App\Model\Entity\SuserDomain newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SuserDomain[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SuserDomain|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SuserDomain patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SuserDomain[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SuserDomain findOrCreate($search, callable $callback = null, $options = [])
 */
class SuserDomainsTable extends AppTable
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

        $this->setTable('suser_domains');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Susers', [
            'foreignKey' => 'suser_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Sroles', [
            'foreignKey' => 'srole_id',
            'joinType' => 'INNER'
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('default_domain', 'create')
            ->notEmpty('default_domain');

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
        $rules->add($rules->existsIn(['suser_id'], 'Susers'));
        $rules->add($rules->existsIn(['domain_id'], 'Domains'));
        $rules->add($rules->existsIn(['srole_id'], 'Sroles'));

        return $rules;
    }
}
