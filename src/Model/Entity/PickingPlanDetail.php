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
 * PickingPlanDetail Entity
 *
 * @property int $id
 * @property int $domain_id
 * @property int $picking_plan_id
 * @property string $picking_type
 * @property int $asset_id
 * @property int $classification_id
 * @property int $product_id
 * @property int $product_model_id
 * @property string $asset_type
 * @property int $plan_count
 * @property string $detail_sts
 * @property string $apply_no
 * @property int $kitting_pattern_id
 * @property string $remarks
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Domain $domain
 * @property \App\Model\Entity\PickingPlan $picking_plan
 * @property \App\Model\Entity\Asset $asset
 * @property \App\Model\Entity\Classification $classification
 * @property \App\Model\Entity\Product $product
 * @property \App\Model\Entity\ProductModel $product_model
 * @property \App\Model\Entity\KittingPattern $kitting_pattern
 * @property \App\Model\Entity\Picking[] $pickings
 */
class PickingPlanDetail extends AppEntity
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
