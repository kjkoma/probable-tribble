<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AssetAttributesFixture
 *
 */
class AssetAttributesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'ID', 'autoIncrement' => true, 'precision' => null],
        'domain_id' => ['type' => 'smallinteger', 'length' => 5, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'ドメインID - domains.id', 'precision' => null],
        'asset_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '資産ID', 'precision' => null, 'autoIncrement' => null],
        'gw' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'GWアドレス', 'precision' => null, 'fixed' => null],
        'ip' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'IPアドレス', 'precision' => null, 'fixed' => null],
        'ip_v6' => ['type' => 'string', 'length' => 39, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'IPアドレス（v6）', 'precision' => null, 'fixed' => null],
        'ip_wifi' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'IPアドレス（無線）', 'precision' => null, 'fixed' => null],
        'mac' => ['type' => 'string', 'length' => 18, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'MACアドレス', 'precision' => null, 'fixed' => null],
        'mac_wifi' => ['type' => 'string', 'length' => 18, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'MACアドレス（無線）', 'precision' => null, 'fixed' => null],
        'subnet' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'サブネット', 'precision' => null, 'fixed' => null],
        'dns' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'DNS', 'precision' => null, 'fixed' => null],
        'dhcp' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'DHCP', 'precision' => null, 'fixed' => null],
        'os' => ['type' => 'string', 'length' => 80, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'OS', 'precision' => null, 'fixed' => null],
        'os_version' => ['type' => 'string', 'length' => 80, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'OS（バージョン）', 'precision' => null, 'fixed' => null],
        'office' => ['type' => 'string', 'length' => 80, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Office', 'precision' => null, 'fixed' => null],
        'office_remarks' => ['type' => 'string', 'length' => 256, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Office（補足）', 'precision' => null, 'fixed' => null],
        'software' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'ソフトウェア', 'precision' => null],
        'imei_no' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'IMEI番号', 'precision' => null, 'fixed' => null],
        'certificate_no' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '証明書番号', 'precision' => null, 'fixed' => null],
        'apply_no' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '購入申請番号', 'precision' => null, 'fixed' => null],
        'place' => ['type' => 'string', 'length' => 80, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '保管場所', 'precision' => null, 'fixed' => null],
        'purchase_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '購入日', 'precision' => null],
        'support_term_year' => ['type' => 'tinyinteger', 'length' => 4, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => 'サポート期間（年）', 'precision' => null],
        'at_mouse' => ['type' => 'string', 'length' => 80, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '付属マウス', 'precision' => null, 'fixed' => null],
        'at_keyboard' => ['type' => 'string', 'length' => 80, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '付属キーボード', 'precision' => null, 'fixed' => null],
        'at_ac' => ['type' => 'string', 'length' => 80, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '付属AC', 'precision' => null, 'fixed' => null],
        'at_manual' => ['type' => 'string', 'length' => 80, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '付属マニュアル類', 'precision' => null, 'fixed' => null],
        'at_other' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '付属その他', 'precision' => null],
        'local_user' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '管理ユーザー（ローカル）', 'precision' => null, 'fixed' => null],
        'local_password' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '管理パスワード（ローカル）', 'precision' => null, 'fixed' => null],
        'uefi_password' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'UEFIパスワード(supervisor)', 'precision' => null, 'fixed' => null],
        'uefi_user_password' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'UEFIパスワード(user)', 'precision' => null, 'fixed' => null],
        'hdd_password' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'HDDパスワード(supervisor)', 'precision' => null, 'fixed' => null],
        'hdd_user_password' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'HDDパスワード(user)', 'precision' => null, 'fixed' => null],
        'dsts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => 'データステータス(0:停止/1:使用中)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'asset_attributes_udx' => ['type' => 'unique', 'columns' => ['domain_id', 'asset_id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'domain_id' => 1,
            'asset_id' => 1,
            'gw' => 'Lorem ipsum d',
            'ip' => 'Lorem ipsum d',
            'ip_v6' => 'Lorem ipsum dolor sit amet',
            'ip_wifi' => 'Lorem ipsum d',
            'mac' => 'Lorem ipsum dolo',
            'mac_wifi' => 'Lorem ipsum dolo',
            'subnet' => 'Lorem ipsum d',
            'dns' => 'Lorem ipsum d',
            'dhcp' => 'Lorem ipsum d',
            'os' => 'Lorem ipsum dolor sit amet',
            'os_version' => 'Lorem ipsum dolor sit amet',
            'office' => 'Lorem ipsum dolor sit amet',
            'office_remarks' => 'Lorem ipsum dolor sit amet',
            'software' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'imei_no' => 'Lorem ipsum dolor sit amet',
            'certificate_no' => 'Lorem ipsum dolor sit amet',
            'apply_no' => 'Lorem ipsum dolor sit amet',
            'place' => 'Lorem ipsum dolor sit amet',
            'purchase_date' => '2017-12-21',
            'support_term_year' => 1,
            'at_mouse' => 'Lorem ipsum dolor sit amet',
            'at_keyboard' => 'Lorem ipsum dolor sit amet',
            'at_ac' => 'Lorem ipsum dolor sit amet',
            'at_manual' => 'Lorem ipsum dolor sit amet',
            'at_other' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'local_user' => 'Lorem ipsum dolor sit amet',
            'local_password' => 'Lorem ipsum dolor sit amet',
            'uefi_password' => 'Lorem ipsum dolor sit amet',
            'uefi_user_password' => 'Lorem ipsum dolor sit amet',
            'hdd_password' => 'Lorem ipsum dolor sit amet',
            'hdd_user_password' => 'Lorem ipsum dolor sit amet',
            'dsts' => 1,
            'created_at' => '2017-12-21 14:02:31',
            'created_user' => 1,
            'modified_at' => '2017-12-21 14:02:31',
            'modified_user' => 1
        ],
    ];
}
