<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SuserDomainsFixture
 *
 */
class SuserDomainsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'ID', 'autoIncrement' => true, 'precision' => null],
        'suser_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'システムユーザーID - susers.id', 'precision' => null, 'autoIncrement' => null],
        'domain_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'ドメインID - domains.id', 'precision' => null, 'autoIncrement' => null],
        'srole_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'システムロールID - sroles.id', 'precision' => null, 'autoIncrement' => null],
        'default_domain' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '0', 'comment' => 'デフォルトドメイン(0: デフォルト以外、1: デフォルト／デフォルト：0)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'suser_domains_udx' => ['type' => 'unique', 'columns' => ['suser_id', 'domain_id'], 'length' => []],
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
            'suser_id' => 1,
            'domain_id' => 1,
            'srole_id' => 1,
            'default_domain' => 1,
            'created_at' => '2017-11-07 11:52:41',
            'created_user' => 1,
            'modified_at' => '2017-11-07 11:52:41',
            'modified_user' => 1
        ],
    ];
}
