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
 * Instocks Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\InstockPlansTable|\Cake\ORM\Association\BelongsTo $InstockPlans
 * @property \App\Model\Table\InstockSusersTable|\Cake\ORM\Association\BelongsTo $InstockSusers
 * @property \App\Model\Table\ConfirmSusersTable|\Cake\ORM\Association\BelongsTo $ConfirmSusers
 * @property \App\Model\Table\DeliveryCompaniesTable|\Cake\ORM\Association\BelongsTo $DeliveryCompanies
 * @property \App\Model\Table\AssetUsersTable|\Cake\ORM\Association\HasMany $AssetUsers
 * @property \App\Model\Table\InstockDetailsTable|\Cake\ORM\Association\HasMany $InstockDetails
 * @property \App\Model\Table\StockHistoriesTable|\Cake\ORM\Association\HasMany $StockHistories
 *
 * @method \App\Model\Entity\Instock get($primaryKey, $options = [])
 * @method \App\Model\Entity\Instock newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Instock[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Instock|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Instock patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Instock[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Instock findOrCreate($search, callable $callback = null, $options = [])
 */
class InstocksTable extends AppTable
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

        $this->setTable('instocks');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('InstockPlans', [
            'foreignKey' => 'instock_plan_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('InstockSusers', [
            'foreignKey' => 'instock_suser_id',
            'className'  => 'Susers'
        ]);
        $this->belongsTo('ConfirmSusers', [
            'foreignKey' => 'confirm_suser_id',
            'className'  => 'Susers'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'delivery_company_id'
        ]);
        $this->hasMany('AssetUsers', [
            'foreignKey' => 'instock_id'
        ]);
        $this->hasMany('InstockDetails', [
            'foreignKey' => 'instock_id',
            'dependent'  => true
        ]);
        $this->hasMany('StockHistories', [
            'foreignKey' => 'instock_id'
        ]);
        $this->belongsTo('InstockKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'nid',
            'bindingKey' => 'instcok_kbn',
            'conditions' => ['InstockKbn.nkey' => 'INSTOCK_KBN']
        ]);

        $this->_sorted = [
            'Instocks.instock_date'     => 'DESC',
            'Instocks.instock_plan_id'  => 'ASC',
            'Instocks.instcok_suser_id' => 'ASC',
            'Instocks.instock_kbn'      => 'ASC',
            'Instocks.id'               => 'ASC'
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
            ->scalar('instock_kbn')
            ->requirePresence('instock_kbn', 'create')
            ->notEmpty('instock_kbn');

        $validator
            ->date('instock_date')
            ->requirePresence('instock_date', 'create')
            ->notEmpty('instock_date');

        $validator
            ->integer('instock_count')
            ->requirePresence('instock_count', 'create')
            ->notEmpty('instock_count');

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
        $rules->add($rules->existsIn(['instock_plan_id'], 'InstockPlans'));
        $rules->add($rules->existsIn(['instock_suser_id'], 'Susers'));
        $rules->add($rules->existsIn(['confirm_suser_id'], 'Susers'));
        $rules->add($rules->existsIn(['delivery_company_id'], 'Companies'));

        return $rules;
    }
}
