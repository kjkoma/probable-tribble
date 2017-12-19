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
 * Zips Model
 *
 * @method \App\Model\Entity\Zip get($primaryKey, $options = [])
 * @method \App\Model\Entity\Zip newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Zip[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Zip|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Zip patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Zip[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Zip findOrCreate($search, callable $callback = null, $options = [])
 */
class ZipsTable extends AppTable
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

        $this->setTable('zips');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->_sorted   = ['Zips.zip' => 'ASC'];
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
            ->scalar('zip')
            ->requirePresence('zip', 'create')
            ->notEmpty('zip');

        $validator
            ->scalar('state')
            ->requirePresence('state', 'create')
            ->notEmpty('state');

        $validator
            ->scalar('city')
            ->requirePresence('city', 'create')
            ->notEmpty('city');

        $validator
            ->scalar('town')
            ->requirePresence('town', 'create')
            ->notEmpty('town');

        $validator
            ->scalar('state_kn')
            ->requirePresence('state_kn', 'create')
            ->notEmpty('state_kn');

        $validator
            ->scalar('city_kn')
            ->requirePresence('city_kn', 'create')
            ->notEmpty('city_kn');

        $validator
            ->scalar('town_kn')
            ->requirePresence('town_kn', 'create')
            ->notEmpty('town_kn');

        return $validator;
    }
}
