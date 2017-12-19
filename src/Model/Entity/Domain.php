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
 * Domain Entity
 *
 * @property int $id
 * @property string $kname
 * @property string $name
 * @property string $remarks
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\App[] $apps
 * @property \App\Model\Entity\Authority[] $authorities
 * @property \App\Model\Entity\Category[] $categories
 * @property \App\Model\Entity\Class[] $classes
 * @property \App\Model\Entity\Company[] $companies
 * @property \App\Model\Entity\Customer[] $customers
 * @property \App\Model\Entity\Model[] $models
 * @property \App\Model\Entity\Organization[] $organizations
 * @property \App\Model\Entity\Product[] $products
 * @property \App\Model\Entity\RoleAuthority[] $role_authorities
 * @property \App\Model\Entity\Role[] $roles
 * @property \App\Model\Entity\StatusFlow[] $status_flows
 * @property \App\Model\Entity\SuserDomain[] $suser_domains
 * @property \App\Model\Entity\UserRole[] $user_roles
 * @property \App\Model\Entity\User[] $users
 */
class Domain extends AppEntity
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
}
