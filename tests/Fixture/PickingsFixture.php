<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PickingsFixture
 *
 */
class PickingsFixture extends TestFixture
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
        'asset_type' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '資産タイプ - snames[ASSET_TYPE]', 'precision' => null, 'fixed' => null],
        'picking_plan_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '出庫予定ID - picking_plans.id', 'precision' => null, 'autoIncrement' => null],
        'picking_plan_detail_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '出庫予定詳細ID - picking_plan_details.id', 'precision' => null, 'autoIncrement' => null],
        'picking_date' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '出庫日', 'precision' => null],
        'picking_suser_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '出庫担当者 - susers.id', 'precision' => null, 'autoIncrement' => null],
        'confirm_suser_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '出庫確認者 - susers.id', 'precision' => null, 'autoIncrement' => null],
        'picking_count' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '出庫数量', 'precision' => null, 'autoIncrement' => null],
        'delivery_company_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '配送業者 - companies.id', 'precision' => null, 'autoIncrement' => null],
        'voucher_no' => ['type' => 'string', 'length' => 40, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '伝票番号', 'precision' => null, 'fixed' => null],
        'remarks' => ['type' => 'string', 'length' => 2048, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '補足', 'precision' => null, 'fixed' => null],
        'dsts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => 'データステータス(0:停止/1:使用中)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'pickings_idx01' => ['type' => 'index', 'columns' => ['domain_id', 'picking_plan_id', 'picking_plan_detail_id'], 'length' => []],
            'pickings_idx02' => ['type' => 'index', 'columns' => ['picking_date'], 'length' => []],
            'pickings_idx03' => ['type' => 'index', 'columns' => ['picking_suser_id', 'confirm_suser_id'], 'length' => []],
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
            'asset_type' => 'Lorem ipsum dolor sit amet',
            'picking_plan_id' => 1,
            'picking_plan_detail_id' => 1,
            'picking_date' => '2017-12-29',
            'picking_suser_id' => 1,
            'confirm_suser_id' => 1,
            'picking_count' => 1,
            'delivery_company_id' => 1,
            'voucher_no' => 'Lorem ipsum dolor sit amet',
            'remarks' => 'Lorem ipsum dolor sit amet',
            'dsts' => 1,
            'created_at' => '2017-12-29 16:36:21',
            'created_user' => 1,
            'modified_at' => '2017-12-29 16:36:21',
            'modified_user' => 1
        ],
    ];
}
