<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Srole Entity
 *
 * @property int $id
 * @property string $kname
 * @property string $name
 * @property int $role_type
 * @property string $remarks
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\SroleSauthority[] $srole_sauthorities
 * @property \App\Model\Entity\SuserDomain[] $suser_domains
 * @property \App\Model\Entity\SuserSrole[] $suser_sroles
 */
class Srole extends Entity
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
        'role_type' => true,
        'remarks' => true,
        'dsts' => true,
        'created_at' => true,
        'created_user' => true,
        'modified_at' => true,
        'modified_user' => true,
        'srole_sauthorities' => true,
        'suser_domains' => true,
        'suser_sroles' => true
    ];
}
