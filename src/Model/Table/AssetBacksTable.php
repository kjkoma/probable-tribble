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
 * AssetBacks Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\InstockPlanDetailsTable|\Cake\ORM\Association\BelongsTo $InstockPlanDetails
 * @property \App\Model\Table\InstocksTable|\Cake\ORM\Association\BelongsTo $Instocks
 * @property \App\Model\Table\InstockAssetsTable|\Cake\ORM\Association\BelongsTo $InstockAssets
 * @property \App\Model\Table\ReqOrganizationsTable|\Cake\ORM\Association\BelongsTo $ReqOrganizations
 * @property \App\Model\Table\ReqUsersTable|\Cake\ORM\Association\BelongsTo $ReqUsers
 * @property \App\Model\Table\RcvSusersTable|\Cake\ORM\Association\BelongsTo $RcvSusers
 *
 * @method \App\Model\Entity\AssetBack get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssetBack newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AssetBack[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssetBack|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssetBack patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssetBack[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssetBack findOrCreate($search, callable $callback = null, $options = [])
 */
class AssetBacksTable extends AppTable
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

        $this->setTable('asset_backs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('InstockPlanDetails', [
            'foreignKey' => 'instock_plan_detail_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Instocks', [
            'foreignKey' => 'instock_id'
        ]);
        $this->belongsTo('Assets', [
            'foreignKey' => 'instock_asset_id'
        ]);
        $this->belongsTo('AssetBacksReqOrganizations', [
            'className'  => 'Organizations',
            'foreignKey' => 'req_organization_id'
        ]);
        $this->belongsTo('AssetBacksReqUsers', [
            'className'  => 'Users',
            'foreignKey' => 'req_user_id'
        ]);
        $this->belongsTo('AssetBacksRcvSusers', [
            'className'  => 'Susers',
            'foreignKey' => 'rcv_suser_id',
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
            ->scalar('assetback_reason')
            ->requirePresence('assetback_reason', 'create')
            ->notEmpty('assetback_reason');

        $validator
            ->scalar('serial_no')
            ->allowEmpty('serial_no');

        $validator
            ->scalar('asset_no')
            ->allowEmpty('asset_no');

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
        $rules->add($rules->existsIn(['instock_plan_detail_id'], 'InstockPlanDetails'));
        $rules->add($rules->existsIn(['instock_id'], 'Instocks'));
        $rules->add($rules->existsIn(['instock_asset_id'], 'Assets'));
        $rules->add($rules->existsIn(['req_organization_id'], 'AssetBacksReqOrganizations'));
        $rules->add($rules->existsIn(['req_user_id'], 'AssetBacksReqUsers'));
        $rules->add($rules->existsIn(['rcv_suser_id'], 'AssetBacksRcvSusers'));

        return $rules;
    }
}
