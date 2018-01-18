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
 * Rentals Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\AssetsTable|\Cake\ORM\Association\BelongsTo $Assets
 * @property \App\Model\Table\ReqOrganizationsTable|\Cake\ORM\Association\BelongsTo $ReqOrganizations
 * @property \App\Model\Table\ReqUsersTable|\Cake\ORM\Association\BelongsTo $ReqUsers
 * @property |\Cake\ORM\Association\BelongsTo $DlvOrganizations
 * @property |\Cake\ORM\Association\BelongsTo $DlvUsers
 * @property \App\Model\Table\RcvSusersTable|\Cake\ORM\Association\BelongsTo $RcvSusers
 * @property \App\Model\Table\ConfirmSusersTable|\Cake\ORM\Association\BelongsTo $ConfirmSusers
 * @property \App\Model\Table\AssetUsersTable|\Cake\ORM\Association\HasMany $AssetUsers
 * @property \App\Model\Table\RentalHistoriesTable|\Cake\ORM\Association\HasMany $RentalHistories
 *
 * @method \App\Model\Entity\Rental get($primaryKey, $options = [])
 * @method \App\Model\Entity\Rental newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Rental[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Rental|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Rental patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Rental[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Rental findOrCreate($search, callable $callback = null, $options = [])
 */
class RentalsTable extends AppTable
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

        $this->setTable('rentals');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Assets', [
            'foreignKey' => 'asset_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('RentalReqOrganizations', [
            'foreignKey' => 'req_organization_id',
            'className'  => 'Organizations'
        ]);
        $this->belongsTo('RentalReqUsers', [
            'foreignKey' => 'req_user_id',
            'className'  => 'Users'
        ]);
        $this->belongsTo('RentalDlvOrganizations', [
            'foreignKey' => 'dlv_organization_id',
            'className'  => 'Organizations'
        ]);
        $this->belongsTo('RentalDlvUsers', [
            'foreignKey' => 'dlv_user_id',
            'className'  => 'Users'
        ]);
        $this->belongsTo('RentalRcvSusers', [
            'foreignKey' => 'rcv_suser_id',
            'className'  => 'Susers'
        ]);
        $this->belongsTo('RentalConfirmSusers', [
            'foreignKey' => 'confirm_suser_id',
            'className'  => 'Susers'
        ]);
        $this->hasMany('AssetUsers', [
            'foreignKey' => 'rental_id'
        ]);
        $this->hasMany('RentalHistories', [
            'foreignKey' => 'rental_id'
        ]);
        $this->belongsTo('RentalSts', [
            'className'  => 'Snames',
            'foreignKey' => 'rental_sts',
            'bindingKey' => 'nid',
            'conditions' => ['RentalSts.nkey' => 'RENTAL_STS']
        ]);
        $this->belongsTo('RentalTimeKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'time_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['RentalTimeKbn.nkey' => 'TIME_KBN']
        ]);

        $this->_sorted = [
            'Rentals.plan_date'   => 'DESC',
            'Rentals.dlv_user_id' => 'ASC',
            'Rentals.req_user_id' => 'ASC'
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
            ->date('req_date')
            ->allowEmpty('req_date');

        $validator
            ->scalar('req_tel')
            ->allowEmpty('req_tel');

        $validator
            ->date('plan_date')
            ->requirePresence('plan_date', 'create')
            ->notEmpty('plan_date');

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
            ->scalar('rental_sts')
            ->allowEmpty('rental_sts');

        $validator
            ->date('rcv_date')
            ->allowEmpty('rcv_date');

        $validator
            ->date('back_plan_date')
            ->allowEmpty('back_plan_date');

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
        $rules->add($rules->existsIn(['asset_id'], 'Assets'));
        $rules->add($rules->existsIn(['req_organization_id'], 'RentalReqOrganizations'));
        $rules->add($rules->existsIn(['req_user_id'], 'RentalReqUsers'));
        $rules->add($rules->existsIn(['dlv_organization_id'], 'RentalDlvOrganizations'));
        $rules->add($rules->existsIn(['dlv_user_id'], 'RentalDlvUsers'));
        $rules->add($rules->existsIn(['rcv_suser_id'], 'RentalRcvSusers'));
        $rules->add($rules->existsIn(['confirm_suser_id'], 'RentalConfirmSusers'));

        return $rules;
    }
}
