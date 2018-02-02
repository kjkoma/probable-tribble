<?php
namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * RepairHistories Model
 *
 * @property \App\Model\Table\DomainsTable|\Cake\ORM\Association\BelongsTo $Domains
 * @property \App\Model\Table\RepairsTable|\Cake\ORM\Association\BelongsTo $Repairs
 * @property |\Cake\ORM\Association\BelongsTo $HistorySusers
 *
 * @method \App\Model\Entity\RepairHistory get($primaryKey, $options = [])
 * @method \App\Model\Entity\RepairHistory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RepairHistory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RepairHistory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RepairHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RepairHistory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RepairHistory findOrCreate($search, callable $callback = null, $options = [])
 */
class RepairHistoriesTable extends AppTable
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

        $this->setTable('repair_histories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Domains', [
            'foreignKey' => 'domain_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Repairs', [
            'foreignKey' => 'repair_id'
        ]);
        $this->belongsTo('RepairHistorySusers', [
            'className'  => 'Susers',
            'foreignKey' => 'history_suser_id'
        ]);
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
            ->date('history_date')
            ->requirePresence('history_date', 'create')
            ->notEmpty('history_date');

        $validator
            ->scalar('history_contents')
            ->requirePresence('history_contents', 'create')
            ->notEmpty('history_contents');

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
        $rules->add($rules->existsIn(['repair_id'], 'Repairs'));
        $rules->add($rules->existsIn(['history_suser_id'], 'RepairHistorySusers'));

        return $rules;
    }
}
