<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * StatesFixture
 *
 */
class StatesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'cd' => ['type' => 'string', 'length' => 2, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '都道府県コード', 'precision' => null, 'fixed' => null],
        'name' => ['type' => 'string', 'length' => 4, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '都道府県名', 'precision' => null, 'fixed' => null],
        'name_kn' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '都道府県名(カナ)', 'precision' => null, 'fixed' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['cd'], 'length' => []],
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
            'cd' => 'b74b708d-1e94-468f-9596-8f0003bef7d9',
            'name' => 'Lo',
            'name_kn' => 'Lorem ip'
        ],
    ];
}
