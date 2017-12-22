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
 * Instock Entity
 *
 * @property int $id
 * @property int $domain_id
 * @property int $instock_plan_id
 * @property \Cake\I18n\FrozenDate $instock_date
 * @property int $instock_suser_id
 * @property int $confirm_suser_id
 * @property int $instock_count
 * @property int $delivery_company_id
 * @property string $voucher_no
 * @property string $remarks
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Domain $domain
 * @property \App\Model\Entity\InstockPlan $instock_plan
 * @property \App\Model\Entity\InstockSuser $instock_suser
 * @property \App\Model\Entity\ConfirmSuser $confirm_suser
 * @property \App\Model\Entity\DeliveryCompany $delivery_company
 * @property \App\Model\Entity\AssetUser[] $asset_users
 * @property \App\Model\Entity\InstockDetail[] $instock_details
 * @property \App\Model\Entity\StockHistory[] $stock_histories
 */
class Instock extends AppEntity
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
