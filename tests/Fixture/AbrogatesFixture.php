<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * AbrogatesFixture
 *
 */
class AbrogatesFixture extends TestFixture
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
        'asset_id' => ['type' => 'biginteger', 'length' => 20, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '資産ID - assets.id', 'precision' => null, 'autoIncrement' => null],
        'req_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '廃棄依頼日', 'precision' => null],
        'req_organization_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '廃棄依頼（組織） - organizations.id', 'precision' => null, 'autoIncrement' => null],
        'req_user_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '廃棄依頼（者） - users.id', 'precision' => null, 'autoIncrement' => null],
        'req_tel' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '廃棄依頼（連絡先）', 'precision' => null, 'fixed' => null],
        'start_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '廃棄開始日', 'precision' => null],
        'end_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '廃棄完了日', 'precision' => null],
        'abrogate_sts' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '廃棄状況', 'precision' => null, 'fixed' => null],
        'rcv_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '廃棄受付日', 'precision' => null],
        'rcv_suser_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '廃棄受付者 - susers.id', 'precision' => null, 'autoIncrement' => null],
        'confirm_suser_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '廃棄確認者 - susers.id', 'precision' => null, 'autoIncrement' => null],
        'cleaning' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'クリーニング - snames[CLEANING]', 'precision' => null, 'fixed' => null],
        'remarks' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '補足', 'precision' => null],
        'dsts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => 'データステータス(0:停止/1:使用中)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'abrogates_idx01' => ['type' => 'index', 'columns' => ['domain_id', 'asset_id', 'req_date'], 'length' => []],
            'abrogates_idx02' => ['type' => 'index', 'columns' => ['req_organization_id', 'req_user_id'], 'length' => []],
            'abrogates_idx03' => ['type' => 'index', 'columns' => ['abrogate_sts'], 'length' => []],
            'abrogates_idx04' => ['type' => 'index', 'columns' => ['rcv_date', 'rcv_suser_id'], 'length' => []],
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
            'asset_id' => 1,
            'req_date' => '2017-12-29',
            'req_organization_id' => 1,
            'req_user_id' => 1,
            'req_tel' => 'Lorem ipsum dolor ',
            'start_date' => '2017-12-29',
            'end_date' => '2017-12-29',
            'abrogate_sts' => 'Lorem ipsum dolor sit amet',
            'rcv_date' => '2017-12-29',
            'rcv_suser_id' => 1,
            'confirm_suser_id' => 1,
            'cleaning' => 'Lorem ipsum dolor sit amet',
            'remarks' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'dsts' => 1,
            'created_at' => '2017-12-29 16:38:28',
            'created_user' => 1,
            'modified_at' => '2017-12-29 16:38:28',
            'modified_user' => 1
        ],
    ];
}
