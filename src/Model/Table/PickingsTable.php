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
 * Pickings Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\PickingPlansTable|\Cake\ORM\Association\BelongsTo $PickingPlans
 * @property \App\Model\Table\PickingPlanDetailsTable|\Cake\ORM\Association\BelongsTo $PickingPlanDetails
 * @property \App\Model\Table\PickingSusersTable|\Cake\ORM\Association\BelongsTo $PickingSusers
 * @property \App\Model\Table\ConfirmSusersTable|\Cake\ORM\Association\BelongsTo $ConfirmSusers
 * @property \App\Model\Table\DeliveryCompaniesTable|\Cake\ORM\Association\BelongsTo $DeliveryCompanies
 * @property \App\Model\Table\AbrogateHistoriesTable|\Cake\ORM\Association\HasMany $AbrogateHistories
 * @property \App\Model\Table\AssetUsersTable|\Cake\ORM\Association\HasMany $AssetUsers
 * @property \App\Model\Table\PickingDetailsTable|\Cake\ORM\Association\HasMany $PickingDetails
 * @property \App\Model\Table\RentalHistoriesTable|\Cake\ORM\Association\HasMany $RentalHistories
 * @property \App\Model\Table\RepairHistoriesTable|\Cake\ORM\Association\HasMany $RepairHistories
 * @property \App\Model\Table\StockHistoriesTable|\Cake\ORM\Association\HasMany $StockHistories
 *
 * @method \App\Model\Entity\Picking get($primaryKey, $options = [])
 * @method \App\Model\Entity\Picking newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Picking[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Picking|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Picking patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Picking[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Picking findOrCreate($search, callable $callback = null, $options = [])
 */
class PickingsTable extends AppTable
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

        $this->setTable('pickings');
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
        $this->belongsTo('PickingPlanDetails', [
            'foreignKey' => 'picking_plan_detail_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PickingSusers', [
            'foreignKey' => 'picking_suser_id',
            'className'  => 'Susers'
        ]);
        $this->belongsTo('PickingConfirmSusers', [
            'foreignKey' => 'confirm_suser_id',
            'className'  => 'Susers'
        ]);
        $this->belongsTo('PickingDeliveryCompanies', [
            'foreignKey' => 'delivery_company_id',
            'className'  => 'Companies'
        ]);
        $this->hasMany('AbrogateHistories', [
            'foreignKey' => 'picking_id'
        ]);
        $this->hasMany('AssetUsers', [
            'foreignKey' => 'picking_id'
        ]);
        $this->hasMany('PickingDetails', [
            'foreignKey' => 'picking_id'
        ]);
        $this->hasMany('RentalHistories', [
            'foreignKey' => 'picking_id'
        ]);
        $this->hasMany('RepairHistories', [
            'foreignKey' => 'picking_id'
        ]);
        $this->hasMany('StockHistories', [
            'foreignKey' => 'picking_id'
        ]);
        $this->belongsTo('PickingKbnName', [
            'className'  => 'Snames',
            'foreignKey' => 'picking_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['PickingKbnName.nkey' => 'PICKING_KBN']
        ]);
        $this->belongsTo('PickingAssetType', [
            'className'  => 'Snames',
            'foreignKey' => 'asset_type',
            'bindingKey' => 'nid',
            'conditions' => ['PickingAssetType.nkey' => 'ASSET_TYPE']
        ]);

        $this->_sorted = [
            'Pickings.picking_date'           => 'DESC',
            'Pickings.picking_plan_id'        => 'ASC',
            'Pickings.picking_plan_detail_id' => 'ASC'
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
            ->scalar('asset_type')
            ->requirePresence('asset_type', 'create')
            ->notEmpty('asset_type');

        $validator
            ->scalar('picking_kbn')
            ->requirePresence('picking_kbn', 'create')
            ->notEmpty('picking_kbn');

        $validator
            ->date('picking_date')
            ->requirePresence('picking_date', 'create')
            ->notEmpty('picking_date');

        $validator
            ->integer('picking_count')
            ->requirePresence('picking_count', 'create')
            ->notEmpty('picking_count');

        $validator
            ->scalar('voucher_no')
            ->allowEmpty('voucher_no');

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
        $rules->add($rules->existsIn(['picking_plan_detail_id'], 'PickingPlanDetails'));
        $rules->add($rules->existsIn(['picking_suser_id'], 'PickingSusers'));
        $rules->add($rules->existsIn(['confirm_suser_id'], 'PickingConfirmSusers'));
        $rules->add($rules->existsIn(['delivery_company_id'], 'PickingDeliveryCompanies'));

        return $rules;
    }
}
