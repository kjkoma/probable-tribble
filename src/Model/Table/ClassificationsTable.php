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
 * Classifications Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\BelongsTo $Categories
 * @property \App\Model\Table\ClassificationsTable|\Cake\ORM\Association\BelongsTo $ParentClassifications
 * @property \App\Model\Table\ClassificationsTable|\Cake\ORM\Association\HasMany $ChildClassifications
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\HasMany $Products
 *
 * @method \App\Model\Entity\Classification get($primaryKey, $options = [])
 * @method \App\Model\Entity\Classification newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Classification[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Classification|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Classification patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Classification[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Classification findOrCreate($search, callable $callback = null, $options = [])
 */
class ClassificationsTable extends AppTable
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

        $this->setTable('classifications');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Products', [
            'foreignKey' => 'classification_id'
        ]);
        $this->hasMany('CAncestorTree', [ // alias
            'foreignKey' => 'ancestor',
            'className'  => 'ClassTree',
            'dependent' => true
        ]);
        $this->hasMany('CDescendantTree', [ // alias
            'foreignKey' => 'descendant',
            'className'  => 'ClassTree',
            'dependent' => true
        ]);
        $this->belongsTo('ClassAssetType', [
            'className'  => 'Snames',
            'foreignKey' => 'nid',
            'bindingKey' => 'asset_type',
            'conditions' => ['ClassAssetType.nkey' => 'ASSET_TYPE']
        ]);

        $this->_sorted   = ['Classifications.kname' => 'ASC'];
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
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->integer('category_id')
            ->requirePresence('category_id', 'create')
            ->notEmpty('category_id');

        $validator
            ->scalar('asset_type')
            ->requirePresence('asset_type', 'create')
            ->notEmpty('asset_type');

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
        $rules->add($rules->existsIn(['category_id'], 'Categories'));

        return $rules;
    }
}
