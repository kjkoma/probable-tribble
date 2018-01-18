<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PickingPlansFixture
 *
 */
class PickingPlansFixture extends TestFixture
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
        'plan_date' => ['type' => 'date', 'length' => null, 'null' => false, 'default' => null, 'comment' => '出庫予定日', 'precision' => null],
        'NAME' => ['type' => 'string', 'length' => 60, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '出庫件名', 'precision' => null, 'fixed' => null],
        'plan_sts' => ['type' => 'string', 'length' => 30, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '出庫状況 - snames[PICKING_STS]', 'precision' => null, 'fixed' => null],
        'req_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '出庫依頼日', 'precision' => null],
        'req_organization_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '出庫依頼（組織） - organizations.id', 'precision' => null, 'autoIncrement' => null],
        'req_user_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '出庫依頼（ユーザー） - users.id', 'precision' => null, 'autoIncrement' => null],
        'dlv_organization_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '出庫先（組織） - organizations.id', 'precision' => null, 'autoIncrement' => null],
        'dlv_user_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '出庫先（ユーザー） - users.id', 'precision' => null, 'autoIncrement' => null],
        'dlv_name' => ['type' => 'string', 'length' => 60, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '出庫先（宛）', 'precision' => null, 'fixed' => null],
        'dlv_zip' => ['type' => 'string', 'length' => 7, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '出庫先（郵便番号）', 'precision' => null, 'fixed' => null],
        'dlv_address' => ['type' => 'string', 'length' => 120, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '出庫先（住所）', 'precision' => null, 'fixed' => null],
        'dlv_tel' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '出庫先（連絡先）', 'precision' => null, 'fixed' => null],
        'arv_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '到着希望日', 'precision' => null],
        'arv_time_kbn' => ['type' => 'string', 'length' => 30, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '到着指定時間区分', 'precision' => null, 'fixed' => null],
        'arv_remarks' => ['type' => 'string', 'length' => 2048, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '到着希望メモ', 'precision' => null, 'fixed' => null],
        'rcv_date' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '受付日', 'precision' => null],
        'rcv_suser_id' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '受付者 - susers.id', 'precision' => null, 'autoIncrement' => null],
        'rcv_reason' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '出庫理由', 'precision' => null],
        'remarks' => ['type' => 'string', 'length' => 2048, 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '補足', 'precision' => null, 'fixed' => null],
        'dsts' => ['type' => 'tinyinteger', 'length' => 3, 'unsigned' => true, 'null' => false, 'default' => '1', 'comment' => 'データステータス(0:停止/1:使用中)', 'precision' => null],
        'created_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '登録日時', 'precision' => null],
        'created_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        'modified_at' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '最終更新日時', 'precision' => null],
        'modified_user' => ['type' => 'integer', 'length' => 8, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '最終更新者', 'precision' => null, 'autoIncrement' => null],
        '_indexes' => [
            'picking_plans_idx01' => ['type' => 'index', 'columns' => ['domain_id', 'plan_date'], 'length' => []],
            'picking_plans_idx02' => ['type' => 'index', 'columns' => ['plan_sts'], 'length' => []],
            'picking_plans_idx03' => ['type' => 'index', 'columns' => ['req_date', 'req_organization_id', 'req_user_id'], 'length' => []],
            'picking_plans_idx04' => ['type' => 'index', 'columns' => ['dlv_organization_id', 'dlv_user_id'], 'length' => []],
            'picking_plans_idx05' => ['type' => 'index', 'columns' => ['rcv_date', 'rcv_suser_id'], 'length' => []],
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
            'plan_date' => '2017-12-29',
            'NAME' => 'Lorem ipsum dolor sit amet',
            'plan_sts' => 'Lorem ipsum dolor sit amet',
            'req_date' => '2017-12-29',
            'req_organization_id' => 1,
            'req_user_id' => 1,
            'dlv_organization_id' => 1,
            'dlv_user_id' => 1,
            'dlv_name' => 'Lorem ipsum dolor sit amet',
            'dlv_zip' => 'Lorem',
            'dlv_address' => 'Lorem ipsum dolor sit amet',
            'dlv_tel' => 'Lorem ipsum dolor ',
            'arv_date' => '2017-12-29',
            'arv_time_kbn' => 'Lorem ipsum dolor sit amet',
            'arv_remarks' => 'Lorem ipsum dolor sit amet',
            'rcv_date' => '2017-12-29',
            'rcv_suser_id' => 1,
            'rcv_reason' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'remarks' => 'Lorem ipsum dolor sit amet',
            'dsts' => 1,
            'created_at' => '2017-12-29 16:36:01',
            'created_user' => 1,
            'modified_at' => '2017-12-29 16:36:01',
            'modified_user' => 1
        ],
    ];
}
