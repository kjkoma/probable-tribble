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
 * Repairs Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property |\Cake\ORM\Association\BelongsTo $RepairAssets
 * @property |\Cake\ORM\Association\BelongsTo $InstockPlanDetails
 * @property |\Cake\ORM\Association\BelongsTo $PickingPlans
 * @property |\Cake\ORM\Association\BelongsTo $PickingAssets
 * @property \App\Model\Table\AssetUsersTable|\Cake\ORM\Association\HasMany $AssetUsers
 * @property \App\Model\Table\RepairHistoriesTable|\Cake\ORM\Association\HasMany $RepairHistories
 *
 * @method \App\Model\Entity\Repair get($primaryKey, $options = [])
 * @method \App\Model\Entity\Repair newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Repair[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Repair|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Repair patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Repair[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Repair findOrCreate($search, callable $callback = null, $options = [])
 */
class RepairsTable extends AppTable
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

        $this->setTable('repairs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('RepairAssets', [
            'className'  => 'Assets',
            'foreignKey' => 'repair_asset_id'
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
        $this->belongsTo('PickingPlans', [
            'foreignKey' => 'picking_plan_id'
        ]);
        $this->belongsTo('RepairsPickingAssets', [
            'className'  => 'Assets',
            'foreignKey' => 'picking_asset_id'
        ]);
        $this->belongsTo('Pickings', [
            'foreignKey' => 'picking_id'
        ]);
        $this->hasMany('RepairHistories', [
            'foreignKey' => 'repair_id'
        ]);
        $this->belongsTo('RepairRepairKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'repair_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['RepairRepairKbn.nkey' => 'REPAIR_KBN']
        ]);
        $this->belongsTo('RepairSts', [
            'className'  => 'Snames',
            'foreignKey' => 'repair_sts',
            'bindingKey' => 'nid',
            'conditions' => ['RepairSts.nkey' => 'REPAIR_STS']
        ]);
        $this->belongsTo('RepairsTroubleKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'trouble_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['RepairsTroubleKbn.nkey' => 'TROUBLE_KBN']
        ]);
        $this->belongsTo('RepairsSendbackKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'sendback_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['RepairsSendbackKbn.nkey' => 'SENDBACK_KBN']
        ]);
        $this->belongsTo('RepairsDatapickKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'datapick_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['RepairsDatapickKbn.nkey' => 'DATAPICK_KBN']
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
            ->scalar('repair_kbn')
            ->requirePresence('repair_kbn', 'create')
            ->notEmpty('repair_kbn');

        $validator
            ->scalar('repair_sts')
            ->requirePresence('repair_sts', 'create')
            ->notEmpty('repair_sts');

        $validator
            ->scalar('trouble_kbn')
            ->requirePresence('trouble_kbn', 'create')
            ->notEmpty('trouble_kbn');

        $validator
            ->scalar('trouble_reason')
            ->requirePresence('trouble_reason', 'create')
            ->notEmpty('trouble_reason');

        $validator
            ->scalar('sendback_kbn')
            ->requirePresence('sendback_kbn', 'create')
            ->notEmpty('sendback_kbn');

        $validator
            ->scalar('datapick_kbn')
            ->requirePresence('datapick_kbn', 'create')
            ->notEmpty('datapick_kbn');

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
        $rules->add($rules->existsIn(['repair_asset_id'], 'RepairAssets'));
        $rules->add($rules->existsIn(['instock_plan_id'], 'InstockPlans'));
        $rules->add($rules->existsIn(['instock_plan_detail_id'], 'InstockPlanDetails'));
        $rules->add($rules->existsIn(['instock_id'], 'Instocks'));
        $rules->add($rules->existsIn(['picking_plan_id'], 'PickingPlans'));
        $rules->add($rules->existsIn(['picking_asset_id'], 'RepairsPickingAssets'));
        $rules->add($rules->existsIn(['picking_id'], 'Pickings'));

        return $rules;
    }
}
