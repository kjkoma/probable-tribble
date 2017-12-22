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
 * InstockPlanDetails Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\InstockPlansTable|\Cake\ORM\Association\BelongsTo $InstockPlans
 * @property \App\Model\Table\AssetsTable|\Cake\ORM\Association\BelongsTo $Assets
 * @property \App\Model\Table\ClassificationsTable|\Cake\ORM\Association\BelongsTo $Classifications
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\ProductModelsTable|\Cake\ORM\Association\BelongsTo $ProductModels
 *
 * @method \App\Model\Entity\InstockPlanDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\InstockPlanDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InstockPlanDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InstockPlanDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InstockPlanDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InstockPlanDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InstockPlanDetail findOrCreate($search, callable $callback = null, $options = [])
 */
class InstockPlanDetailsTable extends AppTable
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

        $this->setTable('instock_plan_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('InstockPlans', [
            'foreignKey' => 'instock_plan_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Assets', [
            'foreignKey' => 'asset_id'
        ]);
        $this->belongsTo('Classifications', [
            'foreignKey' => 'classification_id'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id'
        ]);
        $this->belongsTo('ProductModels', [
            'foreignKey' => 'product_model_id'
        ]);

        $this->_sorted = [
            'InstockPlanDetails.instock_plan_id'   => 'DESC',
            'InstockPlanDetails.instock_type'      => 'ASC',
            'InstockPlanDetails.asset_id'          => 'DESC',
            'InstockPlanDetails.classification_id' => 'ASC',
            'InstockPlanDetails.product_id'        => 'ASC',
            'InstockPlanDetails.product_model_id'  => 'ASC'
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
            ->scalar('instock_type')
            ->requirePresence('instock_type', 'create')
            ->notEmpty('instock_type');

        $validator
            ->requirePresence('plan_count', 'create')
            ->notEmpty('plan_count');

        $validator
            ->scalar('detail_sts')
            ->requirePresence('detail_sts', 'create')
            ->notEmpty('detail_sts');

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
        $rules->add($rules->existsIn(['instock_plan_id'], 'InstockPlans'));
        $rules->add($rules->existsIn(['asset_id'], 'Assets'));
        $rules->add($rules->existsIn(['classification_id'], 'Classifications'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));
        $rules->add($rules->existsIn(['product_model_id'], 'ProductModels'));

        return $rules;
    }
}
