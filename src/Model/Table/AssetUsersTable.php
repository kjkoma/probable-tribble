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
 * AssetUsers Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\AssetsTable|\Cake\ORM\Association\BelongsTo $Assets
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\PickingsTable|\Cake\ORM\Association\BelongsTo $Pickings
 * @property \App\Model\Table\InstocksTable|\Cake\ORM\Association\BelongsTo $Instocks
 * @property \App\Model\Table\RepairsTable|\Cake\ORM\Association\BelongsTo $Repairs
 * @property \App\Model\Table\RentalsTable|\Cake\ORM\Association\BelongsTo $Rentals
 *
 * @method \App\Model\Entity\AssetUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssetUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AssetUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssetUser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssetUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssetUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssetUser findOrCreate($search, callable $callback = null, $options = [])
 */
class AssetUsersTable extends AppTable
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

        $this->setTable('asset_users');
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
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('AssetAdminUsers', [
            'className'  => 'Users',
            'foreignKey' => 'admin_user_id'
        ]);
        $this->belongsTo('Pickings', [
            'foreignKey' => 'picking_id'
        ]);
        $this->belongsTo('Instocks', [
            'foreignKey' => 'instock_id'
        ]);
        $this->belongsTo('AssetUsersUseageTypeName', [
            'className'  => 'Snames',
            'foreignKey' => 'useage_type',
            'bindingKey' => 'nid',
            'conditions' => ['AssetUsersUseageTypeName.nkey' => 'USEAGE_TYPE']
        ]);
        $this->belongsTo('AssetUsersUseageStsName', [
            'className'  => 'Snames',
            'foreignKey' => 'useage_sts',
            'bindingKey' => 'nid',
            'conditions' => ['AssetUsersUseageStsName.nkey' => 'USEAGE_STS']
        ]);

        $this->_sorted = [
            'AssetUsers.asset_id' => 'ASC',
            'AssetUsers.user_id'  => 'ASC'
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
            ->date('start_date')
            ->allowEmpty('start_date');

        $validator
            ->date('end_date')
            ->allowEmpty('end_date');

        $validator
            ->scalar('useage_type')
            ->allowEmpty('useage_type');

        $validator
            ->scalar('useage_sts')
            ->allowEmpty('useage_sts');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['admin_user_id'], 'AssetAdminUsers'));
        $rules->add($rules->existsIn(['picking_id'], 'Pickings'));
        $rules->add($rules->existsIn(['instock_id'], 'Instocks'));

        return $rules;
    }
}
