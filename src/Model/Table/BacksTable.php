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
 * Backs Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\AssetsTable|\Cake\ORM\Association\BelongsTo $Assets
 * @property |\Cake\ORM\Association\BelongsTo $ReqOrganizations
 * @property |\Cake\ORM\Association\BelongsTo $ReqUsers
 * @property |\Cake\ORM\Association\BelongsTo $BackOrganizations
 * @property |\Cake\ORM\Association\BelongsTo $BackUsers
 * @property |\Cake\ORM\Association\BelongsTo $RcvSusers
 * @property |\Cake\ORM\Association\BelongsTo $ConfirmSusers
 * @property \App\Model\Table\BackHistoriesTable|\Cake\ORM\Association\HasMany $BackHistories
 *
 * @method \App\Model\Entity\Back get($primaryKey, $options = [])
 * @method \App\Model\Entity\Back newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Back[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Back|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Back patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Back[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Back findOrCreate($search, callable $callback = null, $options = [])
 */
class BacksTable extends AppTable
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

        $this->setTable('backs');
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
        $this->belongsTo('BackReqOrganizations', [
            'foreignKey' => 'req_organization_id',
            'className'  => 'Organizations'
        ]);
        $this->belongsTo('BackOrganizations', [
            'foreignKey' => 'back_organization_id',
            'className'  => 'Organizations'
        ]);
        $this->belongsTo('BackReqUsers', [
            'foreignKey' => 'req_user_id',
            'className'  => 'Users'
        ]);
        $this->belongsTo('BackUsers', [
            'foreignKey' => 'back_user_id',
            'className'  => 'Users'
        ]);
        $this->belongsTo('BackRcvSusers', [
            'foreignKey' => 'rcv_suser_id',
            'className'  => 'Susers'
        ]);
        $this->belongsTo('BackConfirmSusers', [
            'foreignKey' => 'confirm_suser_id',
            'className'  => 'Susers'
        ]);
        $this->hasMany('BackHistories', [
            'foreignKey' => 'back_id'
        ]);
        $this->belongsTo('BackSts', [
            'className'  => 'Snames',
            'foreignKey' => 'back_sts',
            'bindingKey' => 'nid',
            'conditions' => ['BackSts.nkey' => 'BACK_STS']
        ]);
        $this->belongsTo('BackCleaning', [
            'className'  => 'Snames',
            'foreignKey' => 'cleaning',
            'bindingKey' => 'nid',
            'conditions' => ['BackCleaning.nkey' => 'CLEANING']
        ]);

        $this->_sorted = [
            'Backs.plan_date'    => 'DESC',
            'Backs.back_user_id' => 'ASC',
            'Backs.back_sts'     => 'ASC'
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
            ->date('end_date')
            ->allowEmpty('end_date');

        $validator
            ->scalar('back_sts')
            ->allowEmpty('back_sts');

        $validator
            ->date('rcv_date')
            ->allowEmpty('rcv_date');

        $validator
            ->scalar('cleaning')
            ->allowEmpty('cleaning');

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
        $rules->add($rules->existsIn(['req_organization_id'], 'BackReqOrganizations'));
        $rules->add($rules->existsIn(['back_organization_id'], 'BackOrganizations'));
        $rules->add($rules->existsIn(['req_user_id'], 'BackReqUsers'));
        $rules->add($rules->existsIn(['back_user_id'], 'BackUsers'));
        $rules->add($rules->existsIn(['rcv_suser_id'], 'BackRcvSusers'));
        $rules->add($rules->existsIn(['confirm_suser_id'], 'BackConfirmSusers'));

        return $rules;
    }
}
