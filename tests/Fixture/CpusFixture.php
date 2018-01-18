<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CpusFixture
 *
 */
class CpusFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'cpus';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'ID', 'autoIncrement' => true, 'precision' => null],
        'domain_id' => ['type' => 'smallinteger', 'length' => 5, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'ドメインID - domains.id', 'precision' => null],
        'kname' => ['type' => 'string', 'length' => 12, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '製品名(略称)', 'precision' => null, 'fixed' => null],
        'name' => ['type' => 'string', 'length' => 80, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '製品名', 'precision' => null, 'fixed' => null],
        'maker_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '製造元ID - companies.id（企業区分：製造元が含まれる区分）', 'precision' => null, 'autoIncrement' => null],
        'psts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => '製造ステータス(1:製造中,8:非推奨,9:製造終了)', 'precision' => null],
        'sales_start' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '販売開始日', 'precision' => null],
        'sales_end' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '販売終了日', 'precision' => null],
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
            'kname' => 'Lorem ipsu',
            'name' => 'Lorem ipsum dolor sit amet',
            'maker_id' => 1,
            'psts' => 1,
            'sales_start' => '2017-12-18',
            'sales_end' => '2017-12-18',
            'remarks' => 'Lorem ipsum dolor sit amet',
            'dsts' => 1,
            'created_at' => '2017-12-18 10:19:37',
            'created_user' => 1,
            'modified_at' => '2017-12-18 10:19:37',
            'modified_user' => 1
        ],
    ];
}
