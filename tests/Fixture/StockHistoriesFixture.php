<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StockHistoriesFixture
 *
 */
class StockHistoriesFixture extends TestFixture
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
        'history_type' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '履歴タイプ - snames[HIST_TYPE]', 'precision' => null, 'fixed' => null],
        'instock_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '入庫ID - instocks.id', 'precision' => null, 'autoIncrement' => null],
        'picking_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '出庫ID - pickings.id', 'precision' => null, 'autoIncrement' => null],
        'stocktake_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '棚卸ID - stocktakes.id', 'precision' => null, 'autoIncrement' => null],
        'change_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '変動日時', 'precision' => null],
        'stock_count_org' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '在庫数（元）', 'precision' => null, 'autoIncrement' => null],
        'stock_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '在庫数（変動後）', 'precision' => null, 'autoIncrement' => null],
        'reason_kbn' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '変動理由区分 - snames[STOCK_REASON]', 'precision' => null, 'fixed' => null],
        'reason' => ['type' => 'string', 'length' => 2048, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '変動理由', 'precision' => null, 'fixed' => null],
        'dsts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => 'データステータス(0:停止/1:使用中)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'stock_histories_idx01' => ['type' => 'index', 'columns' => ['domain_id', 'asset_id'], 'length' => []],
            'stock_histories_idx02' => ['type' => 'index', 'columns' => ['history_type', 'instock_id'], 'length' => []],
            'stock_histories_idx03' => ['type' => 'index', 'columns' => ['history_type', 'picking_id'], 'length' => []],
            'stock_histories_idx04' => ['type' => 'index', 'columns' => ['history_type', 'stocktake_id'], 'length' => []],
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
            'history_type' => 'Lorem ipsum dolor sit amet',
            'instock_id' => 1,
            'picking_id' => 1,
            'stocktake_id' => 1,
            'change_at' => '2017-12-21 14:02:56',
            'stock_count_org' => 1,
            'stock_count' => 1,
            'reason_kbn' => 'Lorem ipsum dolor sit amet',
            'reason' => 'Lorem ipsum dolor sit amet',
            'dsts' => 1,
            'created_at' => '2017-12-21 14:02:56',
            'created_user' => 1,
            'modified_at' => '2017-12-21 14:02:56',
            'modified_user' => 1
        ],
    ];
}
