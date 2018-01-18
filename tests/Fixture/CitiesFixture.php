<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CitiesFixture
 *
 */
class CitiesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'cd' => ['type' => 'string', 'length' => 6, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '市区町村コード', 'precision' => null, 'fixed' => null],
        'state' => ['type' => 'string', 'length' => 4, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '都道府県名', 'precision' => null, 'fixed' => null],
        'name' => ['type' => 'string', 'length' => 7, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '市区町村名', 'precision' => null, 'fixed' => null],
        'state_kn' => ['type' => 'string', 'length' => 10, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '都道府県名(カナ)', 'precision' => null, 'fixed' => null],
        'name_kn' => ['type' => 'string', 'length' => 210, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '市区町村名(カナ)', 'precision' => null, 'fixed' => null],
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
            'cd' => 'b729dadb-3bbe-478b-967a-0831634ebb70',
            'state' => 'Lo',
            'name' => 'Lorem',
            'state_kn' => 'Lorem ip',
            'name_kn' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
