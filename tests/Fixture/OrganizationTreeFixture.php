<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrganizationTreeFixture
 *
 */
class OrganizationTreeFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    public $table = 'organization_tree';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'domain_id' => ['type' => 'smallinteger', 'length' => 5, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'ドメインID - domains.id', 'precision' => null],
        'customer_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '資産管理会社ID - customers.id', 'precision' => null, 'autoIncrement' => null],
        'ancestor' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => '先祖組織ID - organizations.id', 'precision' => null, 'autoIncrement' => null],
        'descendant' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => '子孫組織ID - organizations.id', 'precision' => null, 'autoIncrement' => null],
        'is_root' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => 'ルート要素判定(0: ルート以外、1: ルート)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'organization_tree_udx' => ['type' => 'unique', 'columns' => ['domain_id', 'ancestor', 'descendant'], 'length' => []],
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
            'domain_id' => 1,
            'customer_id' => 1,
            'ancestor' => 1,
            'descendant' => 1,
            'is_root' => 1,
            'created_at' => '2017-11-28 16:43:49',
            'created_user' => 1,
            'modified_at' => '2017-11-28 16:43:49',
            'modified_user' => 1
        ],
    ];
}
