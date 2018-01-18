<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AssetUsersFixture
 *
 */
class AssetUsersFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'ID', 'autoIncrement' => true, 'precision' => null],
        'domain_id' => ['type' => 'smallinteger', 'length' => 5, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'ドメインID - domains.id', 'precision' => null],
        'asset_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '資産ID - assets.id', 'precision' => null, 'autoIncrement' => null],
        'user_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '利用者ID - users.id', 'precision' => null, 'autoIncrement' => null],
        'start_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '利用開始日', 'precision' => null],
        'end_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '利用終了日', 'precision' => null],
        'useage_type' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '利用区分 - snames[USEAGE_TYPE]', 'precision' => null, 'fixed' => null],
        'useage_sts' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '利用状況 - snames[USEAGE_STS]', 'precision' => null, 'fixed' => null],
        'picking_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '出庫ID（利用開始） - pickings.id', 'precision' => null, 'autoIncrement' => null],
        'instock_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '入庫ID（利用終了） - instocks.id', 'precision' => null, 'autoIncrement' => null],
        'repair_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '修理ID（修理中） - repairs.id', 'precision' => null, 'autoIncrement' => null],
        'rental_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '貸出ID（貸出中） - rentals.id', 'precision' => null, 'autoIncrement' => null],
        'dsts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => 'データステータス(0:停止/1:使用中)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'asset_users_idx01' => ['type' => 'index', 'columns' => ['domain_id', 'asset_id', 'user_id', 'useage_type', 'useage_sts'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
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
            'user_id' => 1,
            'start_date' => '2017-12-21',
            'end_date' => '2017-12-21',
            'useage_type' => 'Lorem ipsum dolor sit amet',
            'useage_sts' => 'Lorem ipsum dolor sit amet',
            'picking_id' => 1,
            'instock_id' => 1,
            'repair_id' => 1,
            'rental_id' => 1,
            'dsts' => 1,
            'created_at' => '2017-12-21 14:02:36',
            'created_user' => 1,
            'modified_at' => '2017-12-21 14:02:36',
            'modified_user' => 1
        ],
    ];
}
