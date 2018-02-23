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
        $this->belongsTo('RentalReqUsers', [
            'foreignKey' => 'req_user_id',
            'className'  => 'Users'
        ]);
        $this->belongsTo('RentalAdminUsers', [
            'foreignKey' => 'admin_user_id',
            'className'  => 'Users'
        ]);
        $this->belongsTo('RentalUsers', [
            'foreignKey' => 'user_id',
            'className'  => 'Users'
        ]);
        $this->belongsTo('RentalSusers', [
            'foreignKey' => 'rental_suser_id',
            'className'  => 'Susers'
        ]);
        $this->belongsTo('RentalBackUsers', [
            'foreignKey' => 'back_user_id',
            'className'  => 'Users'
        ]);
        $this->belongsTo('RentalBackSusers', [
            'foreignKey' => 'back_suser_id',
            'className'  => 'Susers'
        ]);
        $this->belongsTo('RentalStsName', [
            'className'  => 'Snames',
            'foreignKey' => 'rental_sts',
            'bindingKey' => 'nid',
            'conditions' => ['RentalStsName.nkey' => 'RENTAL_STS']
        ]);

        $this->_sorted = [
            'Rentals.req_date'    => 'DESC',
            'Rentals.rental_date' => 'DESC',
            'Rentals.user_id'     => 'ASC'
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
            ->scalar('rental_sts')
            ->requirePresence('rental_sts', 'create')
            ->notEmpty('rental_sts');

        $validator
            ->date('req_date')
            ->allowEmpty('req_date');

        $validator
            ->date('plan_date')
            ->allowEmpty('plan_date');

        $validator
            ->date('rental_date')
            ->allowEmpty('rental_date');

        $validator
            ->date('back_plan_date')
            ->allowEmpty('back_plan_date');

        $validator
            ->date('back_date')
            ->allowEmpty('back_date');

        $validator
            ->scalar('rental_remarks')
            ->allowEmpty('rental_remarks');

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
        $rules->add($rules->existsIn(['req_user_id'], 'RentalReqUsers'));
        $rules->add($rules->existsIn(['admin_user_id'], 'RentalAdminUsers'));
        $rules->add($rules->existsIn(['user_id'], 'RentalUsers'));
        $rules->add($rules->existsIn(['rental_suser_id'], 'RentalSusers'));
        $rules->add($rules->existsIn(['back_user_id'], 'RentalBackUsers'));
        $rules->add($rules->existsIn(['back_suser_id'], 'RentalBackSusers'));

        return $rules;
    }
}
