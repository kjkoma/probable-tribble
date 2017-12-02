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
 * DomainApps Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\SappsTable|\Cake\ORM\Association\BelongsTo $Sapps
 *
 * @method \App\Model\Entity\DomainApp get($primaryKey, $options = [])
 * @method \App\Model\Entity\DomainApp newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\DomainApp[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\DomainApp|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\DomainApp patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\DomainApp[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\DomainApp findOrCreate($search, callable $callback = null, $options = [])
 */
class DomainAppsTable extends AppTable
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

        $this->setTable('domain_apps');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Sapps', [
            'foreignKey' => 'sapp_id',
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
            ->integer('domain_id')
            ->notEmpty('domain_id');

        $validator
            ->integer('sapp_id')
            ->notEmpty('sapp_id');

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
        $rules->add($rules->existsIn(['sapp_id'], 'Sapps'));

        return $rules;
    }
}
