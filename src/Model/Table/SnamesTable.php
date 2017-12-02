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
 * Snames Model
 *
 * @method \App\Model\Entity\Sname get($primaryKey, $options = [])
 * @method \App\Model\Entity\Sname newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Sname[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Sname|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sname patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Sname[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Sname findOrCreate($search, callable $callback = null, $options = [])
 */
class SnamesTable extends AppTable
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

        $this->setTable('snames');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->_sorted = ['Snames.sort_no' => 'ASC'];
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
            ->scalar('nkey')
            ->requirePresence('nkey', 'create')
            ->notEmpty('nkey');

        $validator
            ->scalar('nid')
            ->requirePresence('nid', 'create')
            ->notEmpty('nid');

        $validator
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->scalar('name2')
            ->allowEmpty('name2');

        $validator
            ->requirePresence('sort_no', 'create')
            ->notEmpty('sort_no');

        $validator
            ->scalar('remarks')
            ->allowEmpty('remarks');

        $validator
            ->requirePresence('dsts', 'create')
            ->notEmpty('dsts');

        $validator
            ->dateTime('created_at')
            ->requirePresence('created_at', 'create')
            ->notEmpty('created_at');

        $validator
            ->integer('created_user')
            ->requirePresence('created_user', 'create')
            ->notEmpty('created_user');

        $validator
            ->dateTime('modified_at')
            ->requirePresence('modified_at', 'create')
            ->notEmpty('modified_at');

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
        $rules->add($rules->isUnique(['nkey', 'nid']));

        return $rules;
    }

    /**
     * キー指定で利用中の一覧を取得する
     * 
     * - - -
     * @param \Cake\ORM\Query $query クエリオブジェクト
     * @param array $options ['nkey']
     * @return \Cake\ORM\Query 実行可能なクエリオブジェクト
     */
    public function findValues(Query $query, array $options)
    {
        $nkey = $options['nkey'];

        return  $query->find('valid')->where(['nkey' => $nkey]);
    }

    /**
     * キーと値指定で利用中の一覧を取得する
     * 
     * - - -
     * @param \Cake\ORM\Query $query クエリオブジェクト
     * @param array $options ['nkey', 'nid']
     * @return \Cake\ORM\Query 実行可能なクエリオブジェクト
     */
    public function findValue(Query $query, array $options)
    {
        $nkey = $options['nkey'];
        $nid  = $options['nid'];

        return  $query->find('valid')->where(['nkey' => $nkey, 'nid' => $nid]);
    }
}
