<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\RepairHistoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\RepairHistoriesTable Test Case
 */
class RepairHistoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\RepairHistoriesTable
     */
    public $RepairHistories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.repair_histories',
        'app.domains',
        'app.domain_apps',
        'app.sapps',
        'app.authorities',
        'app.categories',
        'app.classifications',
        'app.products',
        'app.companies',
        'app.cpus',
        'app.snames',
        'app.product_models',
        'app.product_models_memory_unit',
        'app.product_models_storage_type',
        'app.product_models_storage_unit',
        'app.product_models_support_term_type',
        'app.product_asset_type',
        'app.c_ancestor_tree',
        'app.c_ancestor',
        'app.c_descendant_tree',
        'app.c_descendant',
        'app.class_asset_type',
        'app.customers',
        'app.organization_tree',
        'app.ancestor',
        'app.users',
        'app.organizations',
        'app.ancestor_tree',
        'app.descendant',
        'app.descendant_tree',
        'app.kitting_patterns',
        'app.kitting_patterns_kbn',
        'app.kitting_patterns_type',
        'app.kitting_patterns_reuse_kbn',
        'app.role_authorities',
        'app.roles',
        'app.suser_domains',
        'app.susers',
        'app.sroles',
        'app.srole_sauthorities',
        'app.suser_sroles',
        'app.spasswords',
        'app.user_roles',
        'app.repairs',
        'app.repair_assets',
        'app.instock_plan_details',
        'app.instock_plans',
        'app.instocks',
        'app.instock_susers',
        'app.confirm_susers',
        'app.asset_users',
        'app.assets',
        'app.asset_attributes',
        'app.instock_details',
        'app.stock_histories',
        'app.stocktakes',
        'app.stocktake_susers',
        'app.stocktake_details',
        'app.stocks',
        'app.asset_type',
        'app.asset_sts',
        'app.asset_sub_sts',
        'app.asset_created_suser',
        'app.asset_modified_suser',
        'app.pickings',
        'app.picking_plans',
        'app.picking_plan_req_organizations',
        'app.picking_plan_req_users',
        'app.picking_plan_use_organizations',
        'app.picking_plan_use_users',
        'app.picking_plan_dlv_organizations',
        'app.picking_plan_dlv_users',
        'app.picking_plan_rcv_susers',
        'app.picking_plan_work_susers',
        'app.picking_plan_details',
        'app.picking_plan_detail_asset_type',
        'app.picking_plan_detail_sts',
        'app.picking_plan_picking_kbn',
        'app.picking_plan_sts',
        'app.picking_plan_time_kbn',
        'app.picking_susers',
        'app.picking_confirm_susers',
        'app.picking_delivery_companies',
        'app.abrogate_histories',
        'app.picking_details',
        'app.rental_histories',
        'app.rentals',
        'app.rental_req_organizations',
        'app.rental_req_users',
        'app.rental_dlv_organizations',
        'app.rental_dlv_users',
        'app.rental_rcv_susers',
        'app.rental_confirm_susers',
        'app.rental_sts',
        'app.rental_time_kbn',
        'app.picking_kbn',
        'app.picking_asset_type',
        'app.instocks_instock_kbn',
        'app.instocks_asset_type',
        'app.instock_plans_kbn',
        'app.instock_plans_sts',
        'app.instock_plan_details_sts',
        'app.instock_plan_details_asset_type',
        'app.picking_assets',
        'app.repair_hist_type'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('RepairHistories') ? [] : ['className' => RepairHistoriesTable::class];
        $this->RepairHistories = TableRegistry::get('RepairHistories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->RepairHistories);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
