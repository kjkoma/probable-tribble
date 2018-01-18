<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ZipsFixture
 *
 */
class ZipsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => 'ID', 'autoIncrement' => true, 'precision' => null],
        'zip' => ['type' => 'string', 'length' => 7, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '郵便番号', 'precision' => null, 'fixed' => null],
        'state' => ['type' => 'string', 'length' => 4, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '都道府県名', 'precision' => null, 'fixed' => null],
        'city' => ['type' => 'string', 'length' => 7, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '市区町村名', 'precision' => null, 'fixed' => null],
        'town' => ['type' => 'string', 'length' => 20, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '町名', 'precision' => null, 'fixed' => null],
        'state_kn' => ['type' => 'string', 'length' => 40, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '都道府県名(カナ)', 'precision' => null, 'fixed' => null],
        'city_kn' => ['type' => 'string', 'length' => 40, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '市区町村名(カナ)', 'precision' => null, 'fixed' => null],
        'town_kn' => ['type' => 'string', 'length' => 40, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '町名（カナ）', 'precision' => null, 'fixed' => null],
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
            'zip' => 'Lorem',
            'state' => 'Lo',
            'city' => 'Lorem',
            'town' => 'Lorem ipsum dolor ',
            'state_kn' => 'Lorem ipsum dolor sit amet',
            'city_kn' => 'Lorem ipsum dolor sit amet',
            'town_kn' => 'Lorem ipsum dolor sit amet'
        ],
    ];
}
