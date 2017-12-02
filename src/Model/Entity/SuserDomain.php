<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SuserDomain Entity
 *
 * @property int $id
 * @property int $suser_id
 * @property int $domain_id
 * @property int $srole_id
 * @property int $default_domain
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Suser $suser
 * @property \App\Model\Entity\Domain $domain
 * @property \App\Model\Entity\Srole $srole
 */
class SuserDomain extends Entity
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
        'suser_id' => true,
        'domain_id' => true,
        'srole_id' => true,
        'default_domain' => true,
        'created_at' => true,
        'created_user' => true,
        'modified_at' => true,
        'modified_user' => true,
        'suser' => true,
        'domain' => true,
        'srole' => true
    ];
}
