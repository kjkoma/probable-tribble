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
 * Assets Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\ClassificationsTable|\Cake\ORM\Association\BelongsTo $Classifications
 * @property \App\Model\Table\MakersTable|\Cake\ORM\Association\BelongsTo $Makers
 * @property \App\Model\Table\ProductsTable|\Cake\ORM\Association\BelongsTo $Products
 * @property \App\Model\Table\ProductModelsTable|\Cake\ORM\Association\BelongsTo $ProductModels
 * @property \App\Model\Table\AssetAttributesTable|\Cake\ORM\Association\HasMany $AssetAttributes
 * @property \App\Model\Table\AssetUsersTable|\Cake\ORM\Association\HasMany $AssetUsers
 * @property \App\Model\Table\InstockPlanDetailsTable|\Cake\ORM\Association\HasMany $InstockPlanDetails
 * @property \App\Model\Table\StockHistoriesTable|\Cake\ORM\Association\HasMany $StockHistories
 * @property \App\Model\Table\StocksTable|\Cake\ORM\Association\HasMany $Stocks
 *
 * @method \App\Model\Entity\Asset get($primaryKey, $options = [])
 * @method \App\Model\Entity\Asset newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Asset[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Asset|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Asset patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Asset[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Asset findOrCreate($search, callable $callback = null, $options = [])
 */
class AssetsTable extends AppTable
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

        $this->setTable('assets');
        $this->setDisplayField('kname');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Classifications', [
            'foreignKey' => 'classification_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Companies', [
            'foreignKey' => 'maker_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Products', [
            'foreignKey' => 'product_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('ProductModels', [
            'foreignKey' => 'product_model_id'
        ]);
        $this->hasOne('AssetAttributes', [
            'foreignKey' => 'asset_id',
            'dependent'  => true,
        ]);
        $this->hasMany('AssetUsers', [
            'foreignKey' => 'asset_id',
            'dependent'  => true
        ]);
        $this->hasMany('InstockPlanDetails', [
            'foreignKey' => 'asset_id'
        ]);
        $this->hasMany('InstockDetails', [
            'foreignKey' => 'asset_id'
        ]);
        $this->hasMany('StockHistories', [
            'foreignKey' => 'asset_id'
        ]);
        $this->hasMany('Repairs', [
            'foreignKey' => 'repair_asset_id'
        ]);
        $this->hasOne('Stocks', [
            'foreignKey' => 'asset_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'current_user_id'
        ]);
        $this->belongsTo('AssetTypeName', [
            'className'  => 'Snames',
            'foreignKey' => 'asset_type',
            'bindingKey' => 'nid',
            'conditions' => ['AssetTypeName.nkey' => 'ASSET_TYPE']
        ]);
        $this->belongsTo('AssetStsName', [
            'className'  => 'Snames',
            'foreignKey' => 'asset_sts',
            'bindingKey' => 'nid',
            'conditions' => ['AssetStsName.nkey' => 'ASSET_STS']
        ]);
        $this->belongsTo('AssetSubStsName', [
            'className'  => 'Snames',
            'foreignKey' => 'asset_sub_sts',
            'bindingKey' => 'nid',
            'conditions' => ['AssetSubStsName.nkey' => 'ASSET_SUB_STS']
        ]);
        $this->belongsTo('AssetCreatedSuser', [
            'className'  => 'Snames',
            'foreignKey' => 'asset_sub_sts',
            'bindingKey' => 'nid',
            'conditions' => ['AssetSubStsName.nkey' => 'ASSET_SUB_STS']
        ]);
        $this->belongsTo('AssetAbrogateSuser', [
            'className'  => 'Susers',
            'foreignKey' => 'abrogate_suser_id'
        ]);
        $this->belongsTo('AssetCreatedSuser', [
            'className'  => 'Susers',
            'foreignKey' => 'created_user'
        ]);
        $this->belongsTo('AssetModifiedSuser', [
            'className'  => 'Susers',
            'foreignKey' => 'modified_user'
        ]);

        $this->_sorted = [
            'Assets.maker_id'         => 'ASC',
            'Assets.product_id'       => 'ASC',
            'Assets.product_model_id' => 'ASC',
            'Assets.asset_no'         => 'ASC',
            'Assets.serial_no'        => 'ASC'
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
            ->scalar('asset_type')
            ->requirePresence('asset_type', 'create')
            ->notEmpty('asset_type');

        $validator
            ->scalar('serial_no')
            ->allowEmpty('serial_no');

        $validator
            ->scalar('asset_no')
            ->allowEmpty('asset_no');

        $validator
            ->scalar('kname')
            ->allowEmpty('kname');

        $validator
            ->scalar('asset_sts')
            ->requirePresence('asset_sts', 'create')
            ->notEmpty('asset_sts');

        $validator
            ->scalar('asset_sub_sts')
            ->requirePresence('asset_sub_sts', 'create')
            ->notEmpty('asset_sub_sts');

        $validator
            ->date('first_instock_date')
            ->allowEmpty('first_instock_date');

        $validator
            ->date('account_date')
            ->allowEmpty('account_date');

        $validator
            ->date('abrogate_date')
            ->allowEmpty('abrogate_date');

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
        $rules->add($rules->existsIn(['classification_id'], 'Classifications'));
        $rules->add($rules->existsIn(['maker_id'], 'Companies'));
        $rules->add($rules->existsIn(['product_id'], 'Products'));
        $rules->add($rules->existsIn(['product_model_id'], 'ProductModels'));

        return $rules;
    }
}
