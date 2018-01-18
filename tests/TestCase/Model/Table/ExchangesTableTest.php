<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ExchangesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ExchangesTable Test Case
 */
class ExchangesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ExchangesTable
     */
    public $Exchanges;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.exchanges',
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
        'app.picking_plan_details',
        'app.picking_plans',
        'app.picking_plan_req_organizations',
        'app.picking_plan_req_users',
        'app.picking_plan_use_organizations',
        'app.picking_plan_use_users',
        'app.picking_plan_dlv_organizations',
        'app.picking_plan_dlv_users',
        'app.picking_plan_rcv_susers',
        'app.picking_plan_work_susers',
        'app.pickings',
        'app.picking_susers',
        'app.picking_confirm_susers',
        'app.picking_delivery_companies',
        'app.abrogate_histories',
        'app.asset_users',
        'app.assets',
        'app.asset_attributes',
        'app.instock_plan_details',
        'app.instock_plans',
        'app.instocks',
        'app.instock_susers',
        'app.confirm_susers',
        'app.instock_details',
        'app.stock_histories',
        'app.stocktakes',
        'app.stocktake_susers',
        'app.stocktake_details',
        'app.instocks_instock_kbn',
        'app.instocks_asset_type',
        'app.instock_plans_kbn',
        'app.instock_plans_sts',
        'app.instock_plan_details_sts',
        'app.instock_plan_details_asset_type',
        'app.stocks',
        'app.asset_type',
        'app.asset_sts',
        'app.asset_sub_sts',
        'app.asset_created_suser',
        'app.asset_modified_suser',
        'app.repairs',
        'app.repair_req_organizations',
        'app.repair_req_users',
        'app.repair_rcv_susers',
        'app.repair_confirm_susers',
        'app.repair_histories',
        'app.repair_hist_type',
        'app.repair_sts',
        'app.rentals',
        'app.rental_req_organizations',
        'app.rental_req_users',
        'app.rental_dlv_organizations',
        'app.rental_dlv_users',
        'app.rental_rcv_susers',
        'app.rental_confirm_susers',
        'app.rental_histories',
        'app.rental_sts',
        'app.rental_time_kbn',
        'app.picking_details',
        'app.picking_kbn',
        'app.picking_asset_type',
        'app.picking_plan_picking_kbn',
        'app.picking_plan_sts',
        'app.picking_plan_time_kbn',
        'app.picking_plan_detail_asset_type',
        'app.picking_plan_detail_sts',
        'app.picking_assets',
        'app.instock_assets'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Exchanges') ? [] : ['className' => ExchangesTable::class];
        $this->Exchanges = TableRegistry::get('Exchanges', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Exchanges);

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
