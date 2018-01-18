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
 * Products Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\MakersTable|\Cake\ORM\Association\BelongsTo $Makers
 * @property \App\Model\Table\ClassificationsTable|\Cake\ORM\Association\BelongsTo $Classifications
 * @property \App\Model\Table\ProductModelsTable|\Cake\ORM\Association\HasMany $ProductModels
 *
 * @method \App\Model\Entity\Product get($primaryKey, $options = [])
 * @method \App\Model\Entity\Product newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Product[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Product|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Product[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Product findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductsTable extends AppTable
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

        $this->setTable('products');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'maker_id'
        ]);
        $this->belongsTo('Classifications', [
            'foreignKey' => 'classification_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ProductModels', [
            'foreignKey' => 'product_id',
            'dependent'  => true
        ]);

        $this->belongsTo('ProductAssetType', [
            'className'  => 'Snames',
            'foreignKey' => 'asset_type',
            'bindingKey' => 'nid',
            'conditions' => ['ProductAssetType.nkey' => 'ASSET_TYPE']
        ]);

        $this->_sorted   = [
            'Products.maker_id'          => 'ASC',
            'Products.classification_id' => 'ASC',
            'Products.kname' => 'ASC',
            'Products.psts'  => 'ASC'
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
        $rules->add($rules->existsIn(['maker_id'], 'Companies'));
        $rules->add($rules->existsIn(['classification_id'], 'Classifications'));

        return $rules;
    }
}
