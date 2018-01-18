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
 * InstockPlans Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\InstockPlanDetailsTable|\Cake\ORM\Association\HasMany $InstockPlanDetails
 * @property \App\Model\Table\InstocksTable|\Cake\ORM\Association\HasMany $Instocks
 *
 * @method \App\Model\Entity\InstockPlan get($primaryKey, $options = [])
 * @method \App\Model\Entity\InstockPlan newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InstockPlan[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InstockPlan|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InstockPlan patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InstockPlan[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InstockPlan findOrCreate($search, callable $callback = null, $options = [])
 */
class InstockPlansTable extends AppTable
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

        $this->setTable('instock_plans');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('InstockPlanDetails', [
            'foreignKey' => 'instock_plan_id',
            'dependent'  => true
        ]);
        $this->hasMany('Instocks', [
            'foreignKey' => 'instock_plan_id'
        ]);
        $this->belongsTo('InstockPlansKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'instock_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['InstockPlansKbn.nkey' => 'INSTOCK_KBN']
        ]);
        $this->belongsTo('InstockPlansSts', [
            'className'  => 'Snames',
            'foreignKey' => 'plan_sts',
            'bindingKey' => 'nid',
            'conditions' => ['InstockPlansSts.nkey' => 'INSTOCK_STS']
        ]);
        $this->belongsTo('InstockPlansKbn', [
            'className'  => 'Snames',
            'foreignKey' => 'instock_kbn',
            'bindingKey' => 'nid',
            'conditions' => ['InstockPlansKbn.nkey' => 'INSTOCK_KBN']
        ]);

        $this->_sorted = [
            'InstockPlans.plan_date'   => 'ASC',
            'InstockPlans.instock_kbn' => 'ASC',
            'InstockPlans.name'        => 'ASC'
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
            ->date('plan_date')
            ->requirePresence('plan_date', 'create')
            ->notEmpty('plan_date');

        $validator
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('plan_sts')
            ->requirePresence('plan_sts', 'create')
            ->notEmpty('plan_sts');

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

        return $rules;
    }
}
