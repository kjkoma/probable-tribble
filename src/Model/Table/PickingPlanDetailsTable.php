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
 * PickingPlanDetails Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\PickingPlansTable|\Cake\ORM\Association\BelongsTo $PickingPlans
 * @property \App\Model\Table\AssetsTable|\Cake\ORM\Association\BelongsTo $Assets
 * @property \App\Model\Table\ClassificationsTable|\Cake\ORM\Association\BelongsTo $Classifications
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\ProductModelsTable|\Cake\ORM\Association\BelongsTo $ProductModels
 * @property \App\Model\Table\KittingPatternsTable|\Cake\ORM\Association\BelongsTo $KittingPatterns
 * @property \App\Model\Table\PickingsTable|\Cake\ORM\Association\HasMany $Pickings
 *
 * @method \App\Model\Entity\PickingPlanDetail get($primaryKey, $options = [])
 * @method \App\Model\Entity\PickingPlanDetail newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PickingPlanDetail[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PickingPlanDetail|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PickingPlanDetail patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PickingPlanDetail[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PickingPlanDetail findOrCreate($search, callable $callback = null, $options = [])
 */
class PickingPlanDetailsTable extends AppTable
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
        $this->setTable('picking_plan_details');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PickingPlans', [
            'foreignKey' => 'picking_plan_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Assets', [
            'foreignKey' => 'asset_id'
        ]);
        $this->belongsTo('Categories', [
            'foreignKey' => 'category_id'
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
        $this->belongsTo('KittingPatterns', [
            'foreignKey' => 'kitting_pattern_id'
        ]);
        $this->hasMany('Pickings', [
            'foreignKey' => 'picking_plan_detail_id'
        ]);
        $this->belongsTo('PickingPlanDetailAssetType', [
            'className'  => 'Snames',
            'foreignKey' => 'asset_type',
            'bindingKey' => 'nid',
            'conditions' => ['PickingPlanDetailAssetType.nkey' => 'ASSET_TYPE']
        ]);
        $this->belongsTo('PickingPlanDetailReuseKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'reuse_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['PickingPlanDetailReuseKbn.nkey' => 'REUSE_KBN']
        ]);
        $this->belongsTo('PickingPlanDetailSts', [
            'className'  => 'Snames',
            'foreignKey' => 'detail_sts',
            'bindingKey' => 'nid',
            'conditions' => ['PickingPlanDetailSts.nkey' => 'PICKING_STS']
        ]);

        $this->_sorted = [
            'PickingPlanDetails.picking_plan_id'  => 'DESC',
            'PickingPlanDetails.product_id'       => 'ASC',
            'PickingPlanDetails.apply_no'         => 'ASC'
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
            ->scalar('picking_type')
            ->requirePresence('picking_type', 'create')
            ->notEmpty('picking_type');

        $validator
            ->scalar('asset_type')
            ->allowEmpty('asset_type');

        $validator
            ->requirePresence('plan_count', 'create')
            ->notEmpty('plan_count');

        $validator
            ->scalar('detail_sts')
            ->requirePresence('detail_sts', 'create')
            ->notEmpty('detail_sts');

        $validator
            ->scalar('apply_no')
            ->allowEmpty('apply_no');

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
        $rules->add($rules->existsIn(['picking_plan_id'], 'PickingPlans'));
        $rules->add($rules->existsIn(['asset_id'], 'Assets'));
        $rules->add($rules->existsIn(['category_id'], 'Categories'));
        $rules->add($rules->existsIn(['classification_id'], 'Classifications'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));
        $rules->add($rules->existsIn(['product_model_id'], 'ProductModels'));
        $rules->add($rules->existsIn(['kitting_pattern_id'], 'KittingPatterns'));

        return $rules;
    }
}
