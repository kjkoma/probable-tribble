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
 * Exchanges Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\PickingPlanDetailsTable|\Cake\ORM\Association\BelongsTo $PickingPlanDetails
 * @property \App\Model\Table\PickingsTable|\Cake\ORM\Association\BelongsTo $Pickings
 * @property \App\Model\Table\PickingAssetsTable|\Cake\ORM\Association\BelongsTo $PickingAssets
 * @property \App\Model\Table\InstockPlanDetailsTable|\Cake\ORM\Association\BelongsTo $InstockPlanDetails
 * @property \App\Model\Table\InstocksTable|\Cake\ORM\Association\BelongsTo $Instocks
 * @property \App\Model\Table\InstockAssetsTable|\Cake\ORM\Association\BelongsTo $InstockAssets
 *
 * @method \App\Model\Entity\Exchange get($primaryKey, $options = [])
 * @method \App\Model\Entity\Exchange newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Exchange[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Exchange|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Exchange patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Exchange[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Exchange findOrCreate($search, callable $callback = null, $options = [])
 */
class ExchangesTable extends AppTable
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

        $this->setTable('exchanges');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PickingPlans', [
            'foreignKey' => 'picking_plan_id'
        ]);
        $this->belongsTo('PickingPlanDetails', [
            'foreignKey' => 'picking_plan_detail_id'
        ]);
        $this->belongsTo('Pickings', [
            'foreignKey' => 'picking_id'
        ]);
        $this->belongsTo('ExchangesPickingAssets', [
            'className'  => 'Assets',
            'foreignKey' => 'picking_asset_id'
        ]);
        $this->belongsTo('InstockPlans', [
            'foreignKey' => 'instock_plan_id'
        ]);
        $this->belongsTo('InstockPlanDetails', [
            'foreignKey' => 'instock_plan_detail_id'
        ]);
        $this->belongsTo('Instocks', [
            'foreignKey' => 'instock_id'
        ]);
        $this->belongsTo('ExchangesInstockAssets', [
            'className'  => 'Assets',
            'foreignKey' => 'instock_asset_id'
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
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('exchange_reason')
            ->requirePresence('exchange_reason', 'create')
            ->notEmpty('exchange_reason');

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
        $rules->add($rules->existsIn(['picking_plan_detail_id'], 'PickingPlanDetails'));
        $rules->add($rules->existsIn(['picking_id'], 'Pickings'));
        $rules->add($rules->existsIn(['picking_asset_id'], 'ExchangesPickingAssets'));
        $rules->add($rules->existsIn(['instock_plan_id'], 'InstockPlans'));
        $rules->add($rules->existsIn(['instock_plan_detail_id'], 'InstockPlanDetails'));
        $rules->add($rules->existsIn(['instock_id'], 'Instocks'));
        $rules->add($rules->existsIn(['instock_asset_id'], 'ExchangesInstockAssets'));

        return $rules;
    }
}
