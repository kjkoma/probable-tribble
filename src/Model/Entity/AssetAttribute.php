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
 * AssetAttribute Entity
 *
 * @property int $id
 * @property int $domain_id
 * @property int $asset_id
 * @property string $gw
 * @property string $ip
 * @property string $ip_v6
 * @property string $ip_wifi
 * @property string $mac
 * @property string $mac_wifi
 * @property string $subnet
 * @property string $dns
 * @property string $dhcp
 * @property string $os
 * @property string $os_version
 * @property string $office
 * @property string $office_remarks
 * @property string $software
 * @property string $imei_no
 * @property string $certificate_no
 * @property string $apply_no
 * @property string $place
 * @property \Cake\I18n\FrozenDate $purchase_date
 * @property int $support_term_year
 * @property string $at_mouse
 * @property string $at_keyboard
 * @property string $at_ac
 * @property string $at_manual
 * @property string $at_other
 * @property string $local_user
 * @property string $local_password
 * @property string $uefi_password
 * @property string $uefi_user_password
 * @property string $hdd_password
 * @property string $hdd_user_password
 * @property int $dsts
 * @property \Cake\I18n\FrozenTime $created_at
 * @property int $created_user
 * @property \Cake\I18n\FrozenTime $modified_at
 * @property int $modified_user
 *
 * @property \App\Model\Entity\Domain $domain
 * @property \App\Model\Entity\Asset $asset
 */
class AssetAttribute extends AppEntity
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
