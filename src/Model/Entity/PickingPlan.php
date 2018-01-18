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
 * PickingPlan Entity
 *
 * @property int $id
 * @property int $domain_id
 * @property \Cake\I18n\FrozenDate $plan_date
 * @property string $NAME
 * @property string $plan_sts
 * @property \Cake\I18n\FrozenDate $req_date
 * @property int $req_organization_id
 * @property int $req_user_id
 * @property int $dlv_organization_id
 * @property int $dlv_user_id
 * @property string $dlv_name
 * @property string $dlv_zip
 * @property string $dlv_address
 * @property string $dlv_tel
 * @property \Cake\I18n\FrozenDate $arv_date
 * @property string $arv_time_kbn
 * @property string $arv_remarks
 * @property \Cake\I18n\FrozenDate $rcv_date
 * @property int $rcv_suser_id
 * @property string $rcv_reason
 * @property string $remarks
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Domain $domain
 * @property \App\Model\Entity\ReqOrganization $req_organization
 * @property \App\Model\Entity\ReqUser $req_user
 * @property \App\Model\Entity\DlvOrganization $dlv_organization
 * @property \App\Model\Entity\DlvUser $dlv_user
 * @property \App\Model\Entity\RcvSuser $rcv_suser
 * @property \App\Model\Entity\PickingPlanDetail[] $picking_plan_details
 * @property \App\Model\Entity\Picking[] $pickings
 */
class PickingPlan extends AppEntity
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
        '*' => true,
        'id' => false
    ];
}
