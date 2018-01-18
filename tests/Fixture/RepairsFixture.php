<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * RepairsFixture
 *
 */
class RepairsFixture extends TestFixture
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
        'repair_type' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '修理タイプ - snames[REPAIR_TYPE]', 'precision' => null, 'fixed' => null],
        'repair_sts' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '修理状況 - snames[REPAIR_STS]', 'precision' => null, 'fixed' => null],
        'repair_asset_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '修理資産ID - assets.id', 'precision' => null, 'autoIncrement' => null],
        'instock_plan_detail_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '入庫予定詳細ID - instock_plan_details.id', 'precision' => null, 'autoIncrement' => null],
        'picking_plan_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '出庫予定ID - picking_plans.id', 'precision' => null, 'autoIncrement' => null],
        'picking_asset_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '出庫資産ID - assets.id', 'precision' => null, 'autoIncrement' => null],
        'trouble_kbn' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '故障区分 - snames[TROUBLE_KBN]', 'precision' => null, 'fixed' => null],
        'trouble_reason' => ['type' => 'string', 'length' => 2048, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '故障原因', 'precision' => null, 'fixed' => null],
        'sendback_kbn' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'センドバック有無 - snames[SENDBACK_KBN]', 'precision' => null, 'fixed' => null],
        'data_pick_kbn' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'データ抽出有無 - snames[DATAPICK_KBN]', 'precision' => null, 'fixed' => null],
        'remarks' => ['type' => 'string', 'length' => 2048, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '補足', 'precision' => null, 'fixed' => null],
        'dsts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => 'データステータス(0:停止/1:使用中)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'repairs_idx01' => ['type' => 'index', 'columns' => ['domain_id', 'repair_asset_id'], 'length' => []],
            'repairs_idx02' => ['type' => 'index', 'columns' => ['instock_plan_detail_id'], 'length' => []],
            'repairs_idx03' => ['type' => 'index', 'columns' => ['picking_plan_id'], 'length' => []],
            'repairs_idx04' => ['type' => 'index', 'columns' => ['picking_asset_id'], 'length' => []],
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
            'repair_type' => 'Lorem ipsum dolor sit amet',
            'repair_sts' => 'Lorem ipsum dolor sit amet',
            'repair_asset_id' => 1,
            'instock_plan_detail_id' => 1,
            'picking_plan_id' => 1,
            'picking_asset_id' => 1,
            'trouble_kbn' => 'Lorem ipsum dolor sit amet',
            'trouble_reason' => 'Lorem ipsum dolor sit amet',
            'sendback_kbn' => 'Lorem ipsum dolor sit amet',
            'data_pick_kbn' => 'Lorem ipsum dolor sit amet',
            'remarks' => 'Lorem ipsum dolor sit amet',
            'dsts' => 1,
            'created_at' => '2018-01-05 14:16:22',
            'created_user' => 1,
            'modified_at' => '2018-01-05 14:16:22',
            'modified_user' => 1
        ],
    ];
}
