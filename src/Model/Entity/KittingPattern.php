<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * KittingPattern Entity
 *
 * @property int $id
 * @property int $domain_id
 * @property string $kname
 * @property string $pattern_kbn
 * @property string $pattern_type
 * @property string $reuse_kbn
 * @property string $remarks
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Domain $domain
 */
class KittingPattern extends Entity
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
        'kname' => true,
        'pattern_kbn' => true,
        'pattern_type' => true,
        'reuse_kbn' => true,
        'remarks' => true,
        'dsts' => true,
        'created_at' => true,
        'created_user' => true,
        'modified_at' => true,
        'modified_user' => true,
        'domain' => true
    ];
}
