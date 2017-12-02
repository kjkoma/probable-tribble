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
 */
namespace App\Form;

use App\Model\Validation\AppValidation;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

/**
 * トップ画面用のログインフォームクラス
 *  
 */
class LoginForm extends Form
{
    /**
     * スキーマを定義する
     *  
     * - - -
     * @param \Cake\Form\Schema スキーマ
     * @return \Cake\Form\Schema スキーマ
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('email'   , ['type' => 'string'])
            ->addField('password', ['type' => 'string']);
    }

    /**
     * バリデータを定義する
     *  
     * - - -
     * @param \Cake\Validation\Validator バリデータ
     * @return \Cake\Validation\Validator バリデータ
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator->provider('app', 'App\Model\Validation\AppValidation');

        // フィールドチェック
        $validator = $validator
            ->requirePresence('email'   , true, 'emailフィールドを定義していください。')
            ->requirePresence('password', true, 'passwordフィールドを定義していください。')
        ;

        // 必須
        $validator = $validator
            ->notEmpty('email'   , 'Emailアドレスを入力してください。')
            ->notEmpty('password', 'パスワードを入力してください。')
        ;

        // 文字数
        $validator = $validator
            ->add('email'   , 'maxlength', [ 'rule' => ['maxLength', 255], 'message' => '可能なEmailアドレスの長さは255文字までです。', 'last' => true ])
            ->add('password', 'minlength', [ 'rule' => ['minLength', 6]  , 'message' => 'パスワードの長さは6文字以上で入力してください。' ])
            ->add('password', 'maxlength', [ 'rule' => ['maxLength', 20] , 'message' => '可能なパスワードの長さは20文字までです。', 'last' => true ])
        ;

        // フォーマット
        $validator = $validator
            ->add('email'   , 'format', [ 'rule' => 'emailFormat'           , 'provider' => 'app', 'message' => 'Emailアドレスのフォーマットが正しくありません。' ])
            ->add('password', 'format', [ 'rule' => 'alphaNumericWithSymbol', 'provider' => 'app', 'message' => 'パスワードは半角英数字と記号の組み合わせで入力してください。' ])
        ;

        return $validator;
    }

    /**
     * 処理を実行する（※現在処理なし）
     *  
     * - - -
     * @param array 処理データ
     * @return boolean true:成功 / false:失敗
     */
    protected function _execute(array $data)
    {
        return true;
    }
}
