<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * OrganizationTree Entity
 *
 * @property int $domain_id
 * @property int $customer_id
 * @property int $ancestor
 * @property int $descendant
 * @property int $is_root
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Domain $domain
 * @property \App\Model\Entity\Customer $customer
 */
class OrganizationTree extends Entity
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
        '*' => true
    ];
}
