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
 * OrganizationTree Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 *
 * @method \App\Model\Entity\OrganizationTree get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrganizationTree newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OrganizationTree[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationTree|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrganizationTree patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationTree[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationTree findOrCreate($search, callable $callback = null, $options = [])
 */
class OrganizationTreeTable extends AppTable
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

        $this->setTable('organization_tree');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Ancestor', [ // alias
            'foreignKey' => 'ancestor',
            'className' => 'Organizations'
            ]);
        $this->belongsTo('Descendant', [ // alias
            'foreignKey' => 'descendant',
            'className' => 'Organizations' 
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
            ->integer('ancestor')
            ->requirePresence('ancestor', 'create')
            ->notEmpty('ancestor');

        $validator
            ->integer('descendant')
            ->requirePresence('descendant', 'create')
            ->notEmpty('descendant');

        $validator
            ->requirePresence('is_root', 'create')
            ->notEmpty('is_root');

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
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));

        return $rules;
    }
}
