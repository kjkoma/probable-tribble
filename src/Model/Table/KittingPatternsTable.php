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
 * KittingPatterns Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 *
 * @method \App\Model\Entity\KittingPattern get($primaryKey, $options = [])
 * @method \App\Model\Entity\KittingPattern newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\KittingPattern[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\KittingPattern|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\KittingPattern patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\KittingPattern[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\KittingPattern findOrCreate($search, callable $callback = null, $options = [])
 */
class KittingPatternsTable extends AppTable
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

        $this->setTable('kitting_patterns');
        $this->setDisplayField('kname');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);

        $this->belongsTo('KittingPatternsKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'nid',
            'bindingKey' => 'memory_unit',
            'conditions' => ['KittingPatternsKbn.nkey' => 'PATTERN_KBN']
        ]);

        $this->belongsTo('KittingPatternsType', [
            'className'  => 'Snames',
            'foreignKey' => 'nid',
            'bindingKey' => 'memory_unit',
            'conditions' => ['KittingPatternsType.nkey' => 'PATTERN_TYPE']
        ]);
        $this->belongsTo('KittingPatternsReuseKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'nid',
            'bindingKey' => 'memory_unit',
            'conditions' => ['KittingPatternsReuseKbn.nkey' => 'REUSE_KBN']
        ]);

        $this->_sorted   = [
            'KittingPatterns.pattern_kbn'  => 'ASC',
            'KittingPatterns.pattern_type' => 'ASC',
            'KittingPatterns.reuse_kbn'    => 'ASC',
            'KittingPatterns.kname'        => 'ASC'
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('kname')
            ->requirePresence('kname', 'create')
            ->notEmpty('kname');

        $validator
            ->scalar('pattern_kbn')
            ->requirePresence('pattern_kbn', 'create')
            ->notEmpty('pattern_kbn');

        $validator
            ->scalar('pattern_type')
            ->requirePresence('pattern_type', 'create')
            ->notEmpty('pattern_type');

        $validator
            ->scalar('reuse_kbn')
            ->requirePresence('reuse_kbn', 'create')
            ->notEmpty('reuse_kbn');

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

        return $rules;
    }
}
