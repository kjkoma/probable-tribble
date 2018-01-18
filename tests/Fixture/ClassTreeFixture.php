<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ClassTreeFixture
 *
 */
class ClassTreeFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'class_tree';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'ID', 'autoIncrement' => true, 'precision' => null],
        'domain_id' => ['type' => 'smallinteger', 'length' => 5, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'ドメインID - domains.id', 'precision' => null],
        'ancestor' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => '親分類ID - classifications.id', 'precision' => null, 'autoIncrement' => null],
        'descendant' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => '先祖分類ID - classifications.id', 'precision' => null, 'autoIncrement' => null],
        'neighbor' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => 'データステータス(0:親子以外/1:親子)', 'precision' => null],
        'category_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => 'カテゴリID／最上位階層以外はNULL - categories.id', 'precision' => null, 'autoIncrement' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'class_tree_udx' => ['type' => 'unique', 'columns' => ['domain_id', 'ancestor', 'descendant'], 'length' => []],
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
            'ancestor' => 1,
            'descendant' => 1,
            'neighbor' => 1,
            'category_id' => 1,
            'created_at' => '2017-12-14 13:09:11',
            'created_user' => 1,
            'modified_at' => '2017-12-14 13:09:11',
            'modified_user' => 1
        ],
    ];
}
