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

use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Domains Model
 *
 * @property \App\Model\Table\AppsTable|\Cake\ORM\Association\HasMany $Apps
 * @property \App\Model\Table\AuthoritiesTable|\Cake\ORM\Association\HasMany $Authorities
 * @property \App\Model\Table\CategoriesTable|\Cake\ORM\Association\HasMany $Categories
 * @property \App\Model\Table\ClassesTable|\Cake\ORM\Association\HasMany $Classes
 * @property \App\Model\Table\CompaniesTable|\Cake\ORM\Association\HasMany $Companies
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\HasMany $Customers
 * @property \App\Model\Table\ModelsTable|\Cake\ORM\Association\HasMany $Models
 * @property \App\Model\Table\OrganizationsTable|\Cake\ORM\Association\HasMany $Organizations
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\HasMany $Products
 * @property \App\Model\Table\RoleAuthoritiesTable|\Cake\ORM\Association\HasMany $RoleAuthorities
 * @property \App\Model\Table\RolesTable|\Cake\ORM\Association\HasMany $Roles
 * @property \App\Model\Table\StatusFlowsTable|\Cake\ORM\Association\HasMany $StatusFlows
 * @property \App\Model\Table\SuserDomainsTable|\Cake\ORM\Association\HasMany $SuserDomains
 * @property \App\Model\Table\UserRolesTable|\Cake\ORM\Association\HasMany $UserRoles
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\HasMany $Users
 *
 * @method \App\Model\Entity\Domain get($primaryKey, $options = [])
 * @method \App\Model\Entity\Domain newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Domain[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Domain|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Domain patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Domain[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Domain findOrCreate($search, callable $callback = null, $options = [])
 */
class DomainsTable extends AppTable
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

        $this->setTable('domains');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('DomainApps', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('Authorities', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('Categories', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('Classifications', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('Companies', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('Customers', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('KittingPatterns', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('ProductModels', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('Organizations', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('OrganizationTree', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('Products', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('RoleAuthorities', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('Roles', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('SuserDomains', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('UserRoles', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);
        $this->hasMany('Users', [
            'foreignKey' => 'domain_id',
            'dependent' => true
        ]);

        $this->_sorted   = ['Domains.kname' => 'ASC'];
    }

    /**
     * beforeMarshalイベント（リクエストデータのエンティティマップ前に実行されるイベント）
     *  
     * - - -
     * @param \Cake\Event\Event $event   イベントオブジェクト
     * @param ArrayObject       $data    リクエストデータ
     * @param ArrayObject       $options オプション
     * @return void
     */
    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        $this->_zen2hanT($data, ['kname', 'name', 'dsts']);

        if (isset($data['kname'])) { // knameは大文字に変換する
            $data['kname'] = $this->upper($data['kname']);
        }
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
            ->scalar('kname')
            ->requirePresence('kname', 'create')
            ->notEmpty('kname');

        $validator
            ->scalar('name')
            ->requirePresence('name', 'create')
            ->notEmpty('name');

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
        $rules->add($rules->isUnique(['kname'], __('この表示名（識別子）はすでに利用されています。')));

        return $rules;
    }
}
