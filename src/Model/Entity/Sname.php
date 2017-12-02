<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Sname Entity
 *
 * @property int $id
 * @property string $nkey
 * @property string $nid
 * @property string $name
 * @property string $name2
 * @property int $sort_no
 * @property string $remarks
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 */
class Sname extends Entity
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
        'nkey' => true,
        'nid' => true,
        'name' => true,
        'name2' => true,
        'sort_no' => true,
        'remarks' => true,
        'dsts' => true,
        'created_at' => true,
        'created_user' => true,
        'modified_at' => true,
        'modified_user' => true
    ];
}
