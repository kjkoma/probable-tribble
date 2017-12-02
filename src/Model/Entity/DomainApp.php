<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DomainApp Entity
 *
 * @property int $id
 * @property int $domain_id
 * @property int $sapp_id
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Domain $domain
 * @property \App\Model\Entity\Sapp $sapp
 */
class DomainApp extends Entity
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
        'domain_id' => true,
        'sapp_id' => true,
        'created_at' => true,
        'created_user' => true,
        'modified_at' => true,
        'modified_user' => true,
        'domain' => true,
        'sapp' => true
    ];
}
