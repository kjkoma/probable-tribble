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
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Suser Entity
 *
 * @property int $id
 * @property string $token
 * @property string $email
 * @property string $password
 * @property string $kname
 * @property string $sname
 * @property string $fname
 * @property int $srole_id
 * @property string $remarks
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Spassword[] $spasswords
 * @property \App\Model\Entity\SuserSrole[] $suser_sroles
 * @property \App\Model\Entity\UserRole[] $user_roles
 */
class Suser extends AppEntity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * - - -
     * @var array
     */
    protected $_hidden = [
        'password', 'password_confirmation',
    ];

    /**
     * (Getter)確認用パスワード
     *  
     * パスワードを暗号化するセッターの拡張
     *  
     * - - -
     * @return string 確認用パスワード
     */
    protected function _getPasswordConfirmation()
    {
        $password_confirmation = isset($this->_properties['password_confirmation']) ? 
            $this->_properties['password_confirmation'] : null;
        return $password_confirmation;
    }

    /**
     * (Setter)パスワード
     *  
     * パスワードを暗号化するセッターの拡張
     *  
     * - - -
     * @param $password 確認用パスワード
     * @return string 確認用パスワード
     */
    protected function _setPasswordConfirmation($password_confirmation)
    {
        return $password_confirmation;
    }

}
