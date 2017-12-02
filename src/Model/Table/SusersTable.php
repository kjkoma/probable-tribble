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
 * Susers Model
 *
 * @property \App\Model\Table\SpasswordsTable|\Cake\ORM\Association\HasMany $Spasswords
 * @property \App\Model\Table\SuserSrolesTable|\Cake\ORM\Association\HasMany $SuserSroles
 * @property \App\Model\Table\UserRolesTable|\Cake\ORM\Association\HasMany $UserRoles
 *
 * @method \App\Model\Entity\Suser get($primaryKey, $options = [])
 * @method \App\Model\Entity\Suser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Suser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Suser|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Suser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Suser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Suser findOrCreate($search, callable $callback = null, $options = [])
 */
class SusersTable extends AppTable
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

        $this->setTable('susers');
        $this->setDisplayField('email');
        $this->setPrimaryKey('id');

        $this->belongsTo('Sroles', [
            'foreignKey' => 'srole_id'
        ]);
        $this->hasMany('Spasswords', [
            'foreignKey' => 'suser_id',
            'dependent' => true
        ]);
        $this->hasMany('SuserDomains', [
            'foreignKey' => 'suser_id',
            'dependent' => true
        ]);
        $this->hasMany('SuserSroles', [
            'foreignKey' => 'suser_id',
            'dependent' => true
        ]);
        $this->hasMany('UserRoles', [
            'foreignKey' => 'suser_id',
            'dependent' => true
        ]);

        $this->_sorted   = ['Susers.email' => 'ASC'];
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
        $this->_zen2hanT($data, ['email', 'kname', 'fname', 'sname', 'password', 'password_confirmation', 'dsts']);

        if (isset($data['email'])) { // emailは小文字に変換する
            $data['email'] = $this->lower($data['email']);
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
        $validator->provider('app', 'App\Model\Validation\AppValidation');

        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('email')
            ->notEmpty('email', 'Emailアドレスを入力してください。')
            ->add('email', 'maxlength', ['rule' => ['maxLength', 255], 'message' => '可能なEmailアドレスの長さは255文字までです。', 'last' => true])
            ->add('email', 'format'   , ['rule' => 'emailFormat'     , 'provider' => 'app'  , 'message' => 'Emailアドレスのフォーマットが正しくありません。', 'last' => true])
            ->add('email', 'unique'   , ['rule' => 'validateUnique'  , 'provider' => 'table', 'message' => '指定されたEmailアドレスは既に登録されています。', 'last' => true])
        ;

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
            ->scalar('kname')
            ->requirePresence('kname', 'create')
            ->notEmpty('kname')
            ->add('kname', 'maxlength' , ['rule' => ['maxLength', 16] , 'message' => 'システムユーザー名（表示名）は最大12文字で入力してください。', 'last' => true]);

        $validator
            ->scalar('sname')
            ->requirePresence('sname', 'create')
            ->notEmpty('sname')
            ->add('sname', 'maxlength' , ['rule' => ['maxLength', 16] , 'message' => 'システムユーザー名（姓）は最大16文字で入力してください。', 'last' => true]);

        $validator
            ->scalar('fname')
            ->requirePresence('fname', 'create')
            ->notEmpty('fname')
            ->add('fname', 'maxlength' , ['rule' => ['maxLength', 16] , 'message' => 'システムユーザー名（名）は最大16文字で入力してください。', 'last' => true]);

        $validator
            ->scalar('remarks')
            ->allowEmpty('remarks')
            ->add('remarks', 'maxlength' , ['rule' => ['maxLength', 256] , 'message' => '補足は最大256文字で入力してください。', 'last' => true]);

        $validator
            ->integer('srole_id')
            ->notEmpty('srole_id')
            ->add('srole_id', 'naturalNumber', ['rule' => 'naturalNumber', 'message' => 'ロールが正しくありません。', 'last' => true]);

        $validator
            ->integer('dsts')
            ->allowEmpty('dsts')
            ->add('dsts', 'naturalNumber', ['rule' => 'naturalNumber', 'message' => 'データステータスが正しくありません。', 'last' => true])
            ->add('dsts', 'range'        , ['rule' => ['range', 0, 1], 'message' => 'データステータスが正しくありません。(指定値不明)', 'last' => true]);

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
        $rules->add($rules->isUnique(['token']));
        $rules->add($rules->isUnique(['email']));

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

        // トークン生成
        if (!$entity->isNew()) {
            unset($entity['token']);
        } else {
            $entity->token = $this->createToken();
        }
    }
}
