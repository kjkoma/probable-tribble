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
 * ProductModel Entity
 *
 * @property int $id
 * @property int $domain_id
 * @property string $kname
 * @property string $NAME
 * @property int $product_id
 * @property int $msts
 * @property \Cake\I18n\FrozenDate $sales_start
 * @property \Cake\I18n\FrozenDate $sales_end
 * @property int $cpu_id
 * @property string $memory_unit
 * @property int $MEMORY
 * @property string $storage_type
 * @property int $storage_vol
 * @property string $VERSION
 * @property \Cake\I18n\FrozenDate $maked_date
 * @property string $support_term_type
 * @property int $support_term
 * @property string $remarks
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Domain $domain
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\Cpus $cpus
 */
class ProductModel extends AppEntity
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
        '*'  => true,
        'id' => false
    ];
}
