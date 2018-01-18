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
 * Repair Entity
 *
 * @property int $id
 * @property int $domain_id
 * @property string $repair_type
 * @property string $repair_sts
 * @property int $repair_asset_id
 * @property int $instock_plan_detail_id
 * @property int $picking_plan_id
 * @property int $picking_asset_id
 * @property string $trouble_kbn
 * @property string $trouble_reason
 * @property string $sendback_kbn
 * @property string $data_pick_kbn
 * @property string $remarks
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Domain $domain
 * @property \App\Model\Entity\Asset $asset
 * @property \App\Model\Entity\RepairReqOrganization $repair_req_organization
 * @property \App\Model\Entity\RepairReqUser $repair_req_user
 * @property \App\Model\Entity\RepairRcvSuser $repair_rcv_suser
 * @property \App\Model\Entity\RepairConfirmSuser $repair_confirm_suser
 * @property \App\Model\Entity\AssetUser[] $asset_users
 * @property \App\Model\Entity\RepairHistory[] $repair_histories
 * @property \App\Model\Entity\Sname $repair_st
 */
class Repair extends AppEntity
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
