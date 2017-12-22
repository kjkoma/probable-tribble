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
 * StockHistory Entity
 *
 * @property int $id
 * @property int $domain_id
 * @property int $asset_id
 * @property string $history_type
 * @property int $instock_id
 * @property int $picking_id
 * @property int $stocktake_id
 * @property \Cake\I18n\FrozenTime $change_at
 * @property int $stock_count_org
 * @property int $stock_count
 * @property string $reason_kbn
 * @property string $reason
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Domain $domain
 * @property \App\Model\Entity\Asset $asset
 * @property \App\Model\Entity\Instock $instock
 * @property \App\Model\Entity\Picking $picking
 * @property \App\Model\Entity\Stocktake $stocktake
 */
class StockHistory extends AppEntity
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
