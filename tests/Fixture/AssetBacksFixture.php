<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AssetBacksFixture
 *
 */
class AssetBacksFixture extends TestFixture
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
        'instock_plan_detail_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '入庫予定詳細ID - instock_plan_details.id', 'precision' => null, 'autoIncrement' => null],
        'instock_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '入庫ID - instocks.id', 'precision' => null, 'autoIncrement' => null],
        'instock_asset_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '入庫資産ID - assets.id', 'precision' => null, 'autoIncrement' => null],
        'req_organization_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '返却者（組織） - orgnaizations.id', 'precision' => null, 'autoIncrement' => null],
        'req_user_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '返却者（ユーザー） - users.id', 'precision' => null, 'autoIncrement' => null],
        'rcv_suser_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '受付者 - susers.id', 'precision' => null, 'autoIncrement' => null],
        'exchange_reason' => ['type' => 'string', 'length' => 2048, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '返却理由', 'precision' => null, 'fixed' => null],
        'dsts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => 'データステータス(0:停止/1:使用中)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'asset_backs_idx01' => ['type' => 'index', 'columns' => ['domain_id', 'instock_plan_detail_id'], 'length' => []],
            'asset_backs_idx02' => ['type' => 'index', 'columns' => ['instock_asset_id'], 'length' => []],
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
            'instock_plan_detail_id' => 1,
            'instock_id' => 1,
            'instock_asset_id' => 1,
            'req_organization_id' => 1,
            'req_user_id' => 1,
            'rcv_suser_id' => 1,
            'exchange_reason' => 'Lorem ipsum dolor sit amet',
            'dsts' => 1,
            'created_at' => '2018-01-05 14:15:49',
            'created_user' => 1,
            'modified_at' => '2018-01-05 14:15:49',
            'modified_user' => 1
        ],
    ];
}
