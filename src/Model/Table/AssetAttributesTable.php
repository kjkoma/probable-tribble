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
 * AssetAttributes Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\AssetsTable|\Cake\ORM\Association\BelongsTo $Assets
 *
 * @method \App\Model\Entity\AssetAttribute get($primaryKey, $options = [])
 * @method \App\Model\Entity\AssetAttribute newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AssetAttribute[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AssetAttribute|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AssetAttribute patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AssetAttribute[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AssetAttribute findOrCreate($search, callable $callback = null, $options = [])
 */
class AssetAttributesTable extends AppTable
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

        $this->setTable('asset_attributes');
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

        $this->_sorted   = ['AssetAttributes.id' => 'ASC'];
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
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('gw')
            ->allowEmpty('gw');

        $validator
            ->scalar('ip')
            ->allowEmpty('ip');

        $validator
            ->scalar('ip_v6')
            ->allowEmpty('ip_v6');

        $validator
            ->scalar('ip_wifi')
            ->allowEmpty('ip_wifi');

        $validator
            ->scalar('mac')
            ->allowEmpty('mac');

        $validator
            ->scalar('mac_wifi')
            ->allowEmpty('mac_wifi');

        $validator
            ->scalar('subnet')
            ->allowEmpty('subnet');

        $validator
            ->scalar('dns')
            ->allowEmpty('dns');

        $validator
            ->scalar('dhcp')
            ->allowEmpty('dhcp');

        $validator
            ->scalar('os')
            ->allowEmpty('os');

        $validator
            ->scalar('os_version')
            ->allowEmpty('os_version');

        $validator
            ->scalar('office')
            ->allowEmpty('office');

        $validator
            ->scalar('office_remarks')
            ->allowEmpty('office_remarks');

        $validator
            ->scalar('software')
            ->allowEmpty('software');

        $validator
            ->scalar('imei_no')
            ->allowEmpty('imei_no');

        $validator
            ->scalar('certificate_no')
            ->allowEmpty('certificate_no');

        $validator
            ->scalar('apply_no')
            ->allowEmpty('apply_no');

        $validator
            ->scalar('place')
            ->allowEmpty('place');

        $validator
            ->date('purchase_date')
            ->allowEmpty('purchase_date');

        $validator
            ->allowEmpty('support_term_year');

        $validator
            ->scalar('at_mouse')
            ->allowEmpty('at_mouse');

        $validator
            ->scalar('at_keyboard')
            ->allowEmpty('at_keyboard');

        $validator
            ->scalar('at_ac')
            ->allowEmpty('at_ac');

        $validator
            ->scalar('at_manual')
            ->allowEmpty('at_manual');

        $validator
            ->scalar('at_other')
            ->allowEmpty('at_other');

        $validator
            ->scalar('local_user')
            ->allowEmpty('local_user');

        $validator
            ->scalar('local_password')
            ->allowEmpty('local_password');

        $validator
            ->scalar('uefi_password')
            ->allowEmpty('uefi_password');

        $validator
            ->scalar('uefi_user_password')
            ->allowEmpty('uefi_user_password');

        $validator
            ->scalar('hdd_password')
            ->allowEmpty('hdd_password');

        $validator
            ->scalar('hdd_user_password')
            ->allowEmpty('hdd_user_password');

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

        return $rules;
    }
}
