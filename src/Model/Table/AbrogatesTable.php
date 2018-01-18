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
 * Abrogates Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\AssetsTable|\Cake\ORM\Association\BelongsTo $Assets
 * @property \App\Model\Table\ReqOrganizationsTable|\Cake\ORM\Association\BelongsTo $ReqOrganizations
 * @property \App\Model\Table\ReqUsersTable|\Cake\ORM\Association\BelongsTo $ReqUsers
 * @property \App\Model\Table\RcvSusersTable|\Cake\ORM\Association\BelongsTo $RcvSusers
 * @property \App\Model\Table\ConfirmSusersTable|\Cake\ORM\Association\BelongsTo $ConfirmSusers
 * @property \App\Model\Table\AbrogateHistoriesTable|\Cake\ORM\Association\HasMany $AbrogateHistories
 *
 * @method \App\Model\Entity\Abrogate get($primaryKey, $options = [])
 * @method \App\Model\Entity\Abrogate newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Abrogate[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Abrogate|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Abrogate patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Abrogate[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Abrogate findOrCreate($search, callable $callback = null, $options = [])
 */
class AbrogatesTable extends AppTable
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

        $this->setTable('abrogates');
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
        $this->belongsTo('AbrogateReqOrganizations', [
            'foreignKey' => 'req_organization_id',
            'className'  => 'Organizations'
        ]);
        $this->belongsTo('AbrogateReqUsers', [
            'foreignKey' => 'req_user_id',
            'className'  => 'Users'
        ]);
        $this->belongsTo('AbrogateRcvSusers', [
            'foreignKey' => 'rcv_suser_id',
            'className'  => 'Susers'
        ]);
        $this->belongsTo('AbrogateConfirmSusers', [
            'foreignKey' => 'confirm_suser_id',
            'className'  => 'Susers'
        ]);
        $this->hasMany('AbrogateHistories', [
            'foreignKey' => 'abrogate_id'
        ]);
        $this->belongsTo('AbrogateSts', [
            'className'  => 'Snames',
            'foreignKey' => 'abrogate_sts',
            'bindingKey' => 'nid',
            'conditions' => ['AbrogateSts.nkey' => 'ABROGATE_STS']
        ]);
        $this->belongsTo('AbrogateCleaning', [
            'className'  => 'Snames',
            'foreignKey' => 'cleaning',
            'bindingKey' => 'nid',
            'conditions' => ['AbrogateCleaning.nkey' => 'CLEANING']
        ]);

        $this->_sorted = [
            'Abrogates.req_date'     => 'DESC',
            'Abrogates.req_user_id'  => 'ASC',
            'Abrogates.abrogate_sts' => 'ASC'
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
            ->date('start_date')
            ->allowEmpty('start_date');

        $validator
            ->date('end_date')
            ->allowEmpty('end_date');

        $validator
            ->scalar('abrogate_sts')
            ->allowEmpty('abrogate_sts');

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
        $rules->add($rules->existsIn(['req_organization_id'], 'AbrogateReqOrganizations'));
        $rules->add($rules->existsIn(['req_user_id'], 'AbrogateReqUsers'));
        $rules->add($rules->existsIn(['rcv_suser_id'], 'AbrogateRcvSusers'));
        $rules->add($rules->existsIn(['confirm_suser_id'], 'AbrogateConfirmSusers'));

        return $rules;
    }
}
