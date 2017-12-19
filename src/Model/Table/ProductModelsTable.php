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
 * ProductModels Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\CpusTable|\Cake\ORM\Association\BelongsTo $Cpus
 *
 * @method \App\Model\Entity\ProductModel get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductModel newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductModel[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductModel|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductModel patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductModel[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductModel findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductModelsTable extends AppTable
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

        $this->setTable('product_models');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id'
        ]);
        $this->belongsTo('Cpus', [
            'foreignKey' => 'cpu_id'
        ]);

        $this->_sorted   = [
            'ProductModels.product_id' => 'ASC',
            'ProductModels.kname'      => 'ASC',
            'ProductModels.msts'       => 'ASC',
            'ProductModels.cpu_id'     => 'ASC'
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
            ->requirePresence('msts', 'create')
            ->notEmpty('msts');

        $validator
            ->date('sales_start')
            ->allowEmpty('sales_start');

        $validator
            ->date('sales_end')
            ->allowEmpty('sales_end');

        $validator
            ->scalar('memory_unit')
            ->allowEmpty('memory_unit');

        $validator
            ->integer('memory')
            ->allowEmpty('memory');

        $validator
            ->scalar('storage_type')
            ->allowEmpty('storage_type');

        $validator
            ->integer('storage_vol')
            ->allowEmpty('storage_vol');

        $validator
            ->scalar('version')
            ->allowEmpty('version');

        $validator
            ->date('maked_date')
            ->allowEmpty('maked_date');

        $validator
            ->scalar('support_term_type')
            ->allowEmpty('support_term_type');

        $validator
            ->allowEmpty('support_term');

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
        $rules->add($rules->existsIn(['product_id'], 'Products'));
        $rules->add($rules->existsIn(['cpu_id'], 'Cpus'));

        return $rules;
    }
}
