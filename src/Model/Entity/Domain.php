<?php
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
class Domain extends Entity
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
        'kname' => true,
        'name' => true,
        'remarks' => true,
        'dsts' => true,
        'created_at' => true,
        'created_user' => true,
        'modified_at' => true,
        'modified_user' => true,
        'apps' => true,
        'authorities' => true,
        'categories' => true,
        'classes' => true,
        'companies' => true,
        'customers' => true,
        'models' => true,
        'organizations' => true,
        'products' => true,
        'role_authorities' => true,
        'roles' => true,
        'status_flows' => true,
        'suser_domains' => true,
        'user_roles' => true,
        'users' => true
    ];
}
