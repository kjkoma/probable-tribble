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
 * ClassTree Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsTo $Categories
 *
 * @method \App\Model\Entity\ClassTree get($primaryKey, $options = [])
 * @method \App\Model\Entity\ClassTree newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ClassTree[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ClassTree|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClassTree patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ClassTree[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ClassTree findOrCreate($search, callable $callback = null, $options = [])
 */
class ClassTreeTable extends AppTable
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

        $this->setTable('class_tree');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id'
        ]);
        $this->belongsTo('Ancestor', [ // alias
            'foreignKey'   => 'ancestor',
            'className'    => 'Classifications',
            'propertyName' => 'classification'
        ]);
        $this->belongsTo('Descendant', [ // alias
            'foreignKey'   => 'descendant',
            'className'    => 'Classifications',
            'propertyName' => 'classification'
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
            ->integer('ancestor')
            ->requirePresence('ancestor', 'create')
            ->notEmpty('ancestor');

        $validator
            ->integer('descendant')
            ->requirePresence('descendant', 'create')
            ->notEmpty('descendant');

        $validator
            ->requirePresence('neighbor', 'create')
            ->notEmpty('neighbor');

        $validator
            ->integer('category_id')
            ->allowEmpty('category_id');

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
        $rules->add($rules->existsIn(['category_id'], 'Categories'));

        return $rules;
    }
}
