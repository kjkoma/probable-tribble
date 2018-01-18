<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * InstockPlanDetailsFixture
 *
 */
class InstockPlanDetailsFixture extends TestFixture
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
        'instock_plan_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '入庫予定ID - instock_plans.id', 'precision' => null, 'autoIncrement' => null],
        'instock_type' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '入庫タイプ - snames[INSTOCK_TYPE]', 'precision' => null, 'fixed' => null],
        'asset_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '資産ID - assets.id', 'precision' => null, 'autoIncrement' => null],
        'classification_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '分類ID - classifications.id', 'precision' => null, 'autoIncrement' => null],
        'product_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '製品名 - product_id', 'precision' => null, 'autoIncrement' => null],
        'product_model_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '型番／モデル名 - product_models.id', 'precision' => null, 'autoIncrement' => null],
        'plan_count' => ['type' => 'smallinteger', 'length' => 6, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '入庫予定数量', 'precision' => null],
        'detail_sts' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '入庫明細状況 - snames[INSTOCK_STS]', 'precision' => null, 'fixed' => null],
        'remarks' => ['type' => 'string', 'length' => 512, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '補足', 'precision' => null, 'fixed' => null],
        'dsts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => 'データステータス(0:停止/1:使用中)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'instock_plan_details_idx01' => ['type' => 'index', 'columns' => ['domain_id', 'instock_plan_id'], 'length' => []],
            'instock_plan_details_idx02' => ['type' => 'index', 'columns' => ['instock_type', 'asset_id'], 'length' => []],
            'instock_plan_details_idx03' => ['type' => 'index', 'columns' => ['instock_type', 'classification_id', 'product_id', 'product_model_id'], 'length' => []],
            'instock_plan_details_idx04' => ['type' => 'index', 'columns' => ['detail_sts'], 'length' => []],
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
            'instock_plan_id' => 1,
            'instock_type' => 'Lorem ipsum dolor sit amet',
            'asset_id' => 1,
            'classification_id' => 1,
            'product_id' => 1,
            'product_model_id' => 1,
            'plan_count' => 1,
            'detail_sts' => 'Lorem ipsum dolor sit amet',
            'remarks' => 'Lorem ipsum dolor sit amet',
            'dsts' => 1,
            'created_at' => '2017-12-21 14:03:23',
            'created_user' => 1,
            'modified_at' => '2017-12-21 14:03:23',
            'modified_user' => 1
        ],
    ];
}
