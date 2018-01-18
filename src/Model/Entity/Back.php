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
 * Back Entity
 *
 * @property int $id
 * @property int $domain_id
 * @property int $asset_id
 * @property \Cake\I18n\FrozenDate $req_date
 * @property int $req_organization_id
 * @property int $req_user_id
 * @property string $req_tel
 * @property \Cake\I18n\FrozenDate $plan_date
 * @property \Cake\I18n\FrozenDate $end_date
 * @property int $back_organization_id
 * @property int $back_user_id
 * @property string $back_sts
 * @property \Cake\I18n\FrozenDate $rcv_date
 * @property int $rcv_suser_id
 * @property int $confirm_suser_id
 * @property string $cleaning
 * @property string $remarks
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Domain $domain
 * @property \App\Model\Entity\Asset $asset
 * @property \App\Model\Entity\Organization $back_req_organization
 * @property \App\Model\Entity\Suser $back_req_user
 * @property \App\Model\Entity\Suser $back_rcv_suser
 * @property \App\Model\Entity\Suser $back_confirm_suser
 * @property \App\Model\Entity\BackHistory[] $back_histories
 */
class Back extends AppEntity
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
