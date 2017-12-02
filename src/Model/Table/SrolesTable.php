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

use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sroles Model
 *
 * @property \App\Model\Table\SroleSauthoritiesTable|\Cake\ORM\Association\HasMany $SroleSauthorities
 * @property \App\Model\Table\SuserDomainsTable|\Cake\ORM\Association\HasMany $SuserDomains
 * @property \App\Model\Table\SuserSrolesTable|\Cake\ORM\Association\HasMany $SuserSroles
 *
 * @method \App\Model\Entity\Srole get($primaryKey, $options = [])
 * @method \App\Model\Entity\Srole newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Srole[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Srole|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Srole patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Srole[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Srole findOrCreate($search, callable $callback = null, $options = [])
 */
class SrolesTable extends AppTable
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

        $this->setTable('sroles');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('SroleSauthorities', [
            'foreignKey' => 'srole_id',
            'dependent' => true
        ]);
        $this->hasMany('SuserDomains', [
            'foreignKey' => 'srole_id'
        ]);
        $this->hasMany('SuserSroles', [
            'foreignKey' => 'srole_id',
            'dependent' => true
        ]);
        $this->hasMany('Susers', [
            'foreignKey' => 'srole_id'
        ]);

        $this->_sorted = ['Sroles.id' => 'ASC'];
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
            ->scalar('kname')
            ->requirePresence('kname', 'create')
            ->notEmpty('kname')
            ->add('kname', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->integer('role_type')
            ->requirePresence('name', 'create')
            ->notEmpty('role_type');

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
        $rules->add($rules->isUnique(['kname']));

        return $rules;
    }

    /**
     * システムロールを取得する
     * 
     * - - -
     * @param \Cake\ORM\Query $query クエリオブジェクト
     * @param array $options なし
     * @return \Cake\ORM\Query 実行可能なクエリオブジェクト
     */
    public function findSystems(Query $query, array $options)
    {
        return  $query->find('valid')
            ->where(['role_type' => Configure::read('WNote.DB.Sroles.RoleType.system')]);
    }

    /**
     * ドメインロールを取得する
     * 
     * - - -
     * @param \Cake\ORM\Query $query クエリオブジェクト
     * @param array $options なし
     * @return \Cake\ORM\Query 実行可能なクエリオブジェクト
     */
    public function findDomains(Query $query, array $options)
    {
        return  $query->find('valid')
            ->where(['role_type' => Configure::read('WNote.DB.Sroles.RoleType.domain')]);
    }

    /**
     * 一般ロールを取得する
     * 
     * - - -
     * @param \Cake\ORM\Query $query クエリオブジェクト
     * @param array $options なし
     * @return \Cake\ORM\Query 実行可能なクエリオブジェクト
     */
    public function findPublics(Query $query, array $options)
    {
        return  $query->find('valid')
            ->where(['role_type' => Configure::read('WNote.DB.Sroles.RoleType.public')]);
    }
}
