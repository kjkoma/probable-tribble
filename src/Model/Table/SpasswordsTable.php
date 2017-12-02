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
use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Spasswords Model
 *
 * @property \App\Model\Table\SusersTable|\Cake\ORM\Association\BelongsTo $Susers
 *
 * @method \App\Model\Entity\Spassword get($primaryKey, $options = [])
 * @method \App\Model\Entity\Spassword newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Spassword[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Spassword|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Spassword patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Spassword[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Spassword findOrCreate($search, callable $callback = null, $options = [])
 */
class SpasswordsTable extends AppTable
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

        $this->setTable('spasswords');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Susers', [
            'foreignKey' => 'suser_id',
            'joinType' => 'INNER'
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
        $validator->provider('app', 'App\Model\Validation\AppValidation');

        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('password', 'create')
            ->notEmpty('password', 'パスワードを入力してください。', 'create')
            ->allowEmpty('password', 'update')
            ->add('password', 'minlength'  , ['rule' => ['minLength', 6]        , 'message' => 'パスワードの長さは6文字以上で入力してください。', 'last' => true])
            ->add('password', 'maxlength'  , ['rule' => ['maxLength', 20]       , 'message' => '可能なパスワードの長さは20文字までです。', 'last' => true])
            ->add('password', 'format'     , ['rule' => 'alphaNumericWithSymbol', 'provider' => 'app', 'message' => 'パスワードは半角英数字と記号の組み合わせで入力してください。', 'last' => true ])
            ->add('password', 'compareWith', ['rule' => ['compareWith', 'password_confirmation'], 'message' => '入力されたパスワードが確認用パスワードと一致していません。'])
        ;

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
        $rules->add($rules->existsIn(['suser_id'], 'Susers'));

        return $rules;
    }

    /**
     * 保存前に実行されるイベント
     *  
     * - - -
     * @param \Cake\Event\Event                $event   イベントオブジェクト
     * @param \Cake\Datasource\EntityInterface $entity  エンティティオブジェクト（保存データ）
     * @param \ArrayObject                     $options オプション
     * @return void
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        // パスワード暗号化
        if (!$entity->isNew() && empty($entity->password)) { // 更新時パスワード未設定
            unset($entity['password']);

        } else {
            $entity->password = $this->encrypt($entity->password);
        }
    }
}
