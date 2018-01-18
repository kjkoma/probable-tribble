<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ProductModelsFixture
 *
 */
class ProductModelsFixture extends TestFixture
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
        'kname' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'モデル名(略称)', 'precision' => null, 'fixed' => null],
        'NAME' => ['type' => 'string', 'length' => 120, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'モデル名', 'precision' => null, 'fixed' => null],
        'product_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '製品ID - products.id', 'precision' => null, 'autoIncrement' => null],
        'msts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => 'モデルステータス(1:製造中,8:非推奨,9:製造終了)', 'precision' => null],
        'sales_start' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '販売開始日', 'precision' => null],
        'sales_end' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '販売終了日', 'precision' => null],
        'cpu_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => 'models.id', 'precision' => null, 'autoIncrement' => null],
        'memory_unit' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'メモリ単位 - snames[MEMORY_UNIT]', 'precision' => null, 'fixed' => null],
        'MEMORY' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => 'メモリ', 'precision' => null, 'autoIncrement' => null],
        'storage_type' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'ストレージタイプ - snames[STORAGE_TYPE]', 'precision' => null, 'fixed' => null],
        'storage_vol' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => 'ストレージ容量', 'precision' => null, 'autoIncrement' => null],
        'VERSION' => ['type' => 'string', 'length' => 40, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'バージョン', 'precision' => null, 'fixed' => null],
        'maked_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '製造日', 'precision' => null],
        'support_term_type' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'サポート期間(単位) - snames[SUPPORT_TERM_UNIT]', 'precision' => null, 'fixed' => null],
        'support_term' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => 'サポート期間', 'precision' => null],
        'remarks' => ['type' => 'string', 'length' => 512, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '補足', 'precision' => null, 'fixed' => null],
        'dsts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => 'データステータス(0:停止/1:使用中/2:新規不可)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
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
            'kname' => 'Lorem ipsum dolor sit amet',
            'NAME' => 'Lorem ipsum dolor sit amet',
            'product_id' => 1,
            'msts' => 1,
            'sales_start' => '2017-12-18',
            'sales_end' => '2017-12-18',
            'cpu_id' => 1,
            'memory_unit' => 'Lorem ipsum dolor sit amet',
            'MEMORY' => 1,
            'storage_type' => 'Lorem ipsum dolor sit amet',
            'storage_vol' => 1,
            'VERSION' => 'Lorem ipsum dolor sit amet',
            'maked_date' => '2017-12-18',
            'support_term_type' => 'Lorem ipsum dolor sit amet',
            'support_term' => 1,
            'remarks' => 'Lorem ipsum dolor sit amet',
            'dsts' => 1,
            'created_at' => '2017-12-18 09:50:46',
            'created_user' => 1,
            'modified_at' => '2017-12-18 09:50:46',
            'modified_user' => 1
        ],
    ];
}
