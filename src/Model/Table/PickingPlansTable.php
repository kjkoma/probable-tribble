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
 * PickingPlans Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\ReqOrganizationsTable|\Cake\ORM\Association\BelongsTo $ReqOrganizations
 * @property \App\Model\Table\ReqUsersTable|\Cake\ORM\Association\BelongsTo $ReqUsers
 * @property \App\Model\Table\DlvOrganizationsTable|\Cake\ORM\Association\BelongsTo $DlvOrganizations
 * @property \App\Model\Table\DlvUsersTable|\Cake\ORM\Association\BelongsTo $DlvUsers
 * @property \App\Model\Table\RcvSusersTable|\Cake\ORM\Association\BelongsTo $RcvSusers
 * @property \App\Model\Table\PickingPlanDetailsTable|\Cake\ORM\Association\HasMany $PickingPlanDetails
 * @property \App\Model\Table\PickingsTable|\Cake\ORM\Association\HasMany $Pickings
 *
 * @method \App\Model\Entity\PickingPlan get($primaryKey, $options = [])
 * @method \App\Model\Entity\PickingPlan newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PickingPlan[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PickingPlan|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PickingPlan patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PickingPlan[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PickingPlan findOrCreate($search, callable $callback = null, $options = [])
 */
class PickingPlansTable extends AppTable
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

        $this->setTable('picking_plans');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('PickingPlanReqOrganizations', [
            'foreignKey' => 'req_organization_id',
            'className'  => 'Organizations'
        ]);
        $this->belongsTo('PickingPlanReqUsers', [
            'foreignKey' => 'req_user_id',
            'className'  => 'Users'
        ]);
        $this->belongsTo('PickingPlanUseOrganizations', [
            'foreignKey' => 'use_organization_id',
            'className'  => 'Organizations'
        ]);
        $this->belongsTo('PickingPlanUseUsers', [
            'foreignKey' => 'use_user_id',
            'className'  => 'Users'
        ]);
        $this->belongsTo('PickingPlanDlvOrganizations', [
            'foreignKey' => 'dlv_organization_id',
            'className'  => 'Organizations'
        ]);
        $this->belongsTo('PickingPlanDlvUsers', [
            'foreignKey' => 'dlv_user_id',
            'className'  => 'Users'
        ]);
        $this->belongsTo('PickingPlanRcvSusers', [
            'foreignKey' => 'rcv_suser_id',
            'className'  => 'Susers'
        ]);
        $this->belongsTo('PickingPlanWorkSusers', [
            'foreignKey' => 'work_suser_id',
            'className'  => 'Susers'
        ]);
        $this->hasMany('PickingPlanDetails', [
            'foreignKey' => 'picking_plan_id'
        ]);
        $this->hasMany('Pickings', [
            'foreignKey' => 'picking_plan_id'
        ]);
        $this->belongsTo('PickingPlanPickingKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'picking_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['PickingPlanPickingKbn.nkey' => 'PICKING_KBN']
        ]);
        $this->belongsTo('PickingPlanSts', [
            'className'  => 'Snames',
            'foreignKey' => 'plan_sts',
            'bindingKey' => 'nid',
            'conditions' => ['PickingPlanSts.nkey' => 'PICKING_STS']
        ]);
        $this->belongsTo('PickingPlanTimeKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'arv_time_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['PickingPlanTimeKbn.nkey' => 'TIME_KBN']
        ]);

        $this->_sorted = [
            'PickingPlans.plan_date'     => 'DESC',
            'PickingPlans.dlv_user_id'   => 'ASC',
            'PickingPlans.req_user_id'   => 'ASC'
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
            ->date('plan_date')
            ->allowEmpty('plan_date');

        $validator
            ->scalar('name')
            ->allowEmpty('name');

        $validator
            ->scalar('picking_kbn')
            ->requirePresence('picking_kbn', 'create')
            ->notEmpty('picking_kbn');

        $validator
            ->scalar('plan_sts')
            ->requirePresence('plan_sts', 'create')
            ->notEmpty('plan_sts');

        $validator
            ->date('req_date')
            ->allowEmpty('req_date');

        $validator
            ->scalar('dlv_name')
            ->allowEmpty('dlv_name');

        $validator
            ->scalar('dlv_zip')
            ->allowEmpty('dlv_zip');

        $validator
            ->scalar('dlv_address')
            ->allowEmpty('dlv_address');

        $validator
            ->scalar('dlv_tel')
            ->allowEmpty('dlv_tel');

        $validator
            ->date('arv_date')
            ->allowEmpty('arv_date');

        $validator
            ->scalar('arv_time_kbn')
            ->allowEmpty('arv_time_kbn');

        $validator
            ->scalar('arv_remarks')
            ->allowEmpty('arv_remarks');

        $validator
            ->date('rcv_date')
            ->allowEmpty('rcv_date');

        $validator
            ->scalar('rcv_reason')
            ->allowEmpty('rcv_reason');

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
        $rules->add($rules->existsIn(['req_organization_id'], 'PickingPlanReqOrganizations'));
        $rules->add($rules->existsIn(['use_user_id'], 'PickingPlanReqUsers'));
        $rules->add($rules->existsIn(['req_organization_id'], 'PickingPlanUseOrganizations'));
        $rules->add($rules->existsIn(['use_user_id'], 'PickingPlanUseUsers'));
        $rules->add($rules->existsIn(['dlv_organization_id'], 'PickingPlanDlvOrganizations'));
        $rules->add($rules->existsIn(['dlv_user_id'], 'PickingPlanDlvUsers'));
        $rules->add($rules->existsIn(['rcv_suser_id'], 'PickingPlanRcvSusers'));
        $rules->add($rules->existsIn(['work_suser_id'], 'PickingPlanWorkSusers'));

        return $rules;
    }
}
