<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AssetsFixture
 *
 */
class AssetsFixture extends TestFixture
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
        'classification_id' => ['type' => 'integer', 'length' => 10, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '分類ID - classifications.id', 'precision' => null, 'autoIncrement' => null],
        'serial_no' => ['type' => 'string', 'length' => 120, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '製造番号（シリアル）', 'precision' => null, 'fixed' => null],
        'asset_no' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '資産番号（LCM_NO）', 'precision' => null, 'fixed' => null],
        'maker_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => 'メーカーID - companies.id', 'precision' => null, 'autoIncrement' => null],
        'product_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '製品ID - products.id', 'precision' => null, 'autoIncrement' => null],
        'product_model_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => 'モデルID - product_models.id', 'precision' => null, 'autoIncrement' => null],
        'kname' => ['type' => 'string', 'length' => 100, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '資産名称', 'precision' => null, 'fixed' => null],
        'organization_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '組織ID - organizations.id', 'precision' => null, 'autoIncrement' => null],
        'asset_sts' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '資産状況 - snames[ASSET_STS]', 'precision' => null, 'fixed' => null],
        'remarks' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '補足', 'precision' => null],
        'dsts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => 'データステータス(0:停止/1:使用中)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'assets_idx01' => ['type' => 'index', 'columns' => ['asset_no'], 'length' => []],
            'assets_idx02' => ['type' => 'index', 'columns' => ['maker_id'], 'length' => []],
            'assets_idx03' => ['type' => 'index', 'columns' => ['product_id', 'product_model_id'], 'length' => []],
            'assets_idx04' => ['type' => 'index', 'columns' => ['organization_id'], 'length' => []],
            'assets_idx05' => ['type' => 'index', 'columns' => ['asset_sts'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'assets_udx' => ['type' => 'unique', 'columns' => ['domain_id', 'asset_type', 'classification_id', 'serial_no'], 'length' => []],
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
            'classification_id' => 1,
            'serial_no' => 'Lorem ipsum dolor sit amet',
            'asset_no' => 'Lorem ipsum dolor sit amet',
            'maker_id' => 1,
            'product_id' => 1,
            'product_model_id' => 1,
            'kname' => 'Lorem ipsum dolor sit amet',
            'organization_id' => 1,
            'asset_sts' => 'Lorem ipsum dolor sit amet',
            'remarks' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'dsts' => 1,
            'created_at' => '2017-12-21 14:02:25',
            'created_user' => 1,
            'modified_at' => '2017-12-21 14:02:25',
            'modified_user' => 1
        ],
    ];
}
