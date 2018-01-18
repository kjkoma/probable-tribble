<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PickingDetailsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PickingDetailsTable Test Case
 */
class PickingDetailsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PickingDetailsTable
     */
    public $PickingDetails;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.picking_details',
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
        'app.pickings',
        'app.picking_plans',
        'app.req_organizations',
        'app.req_users',
        'app.dlv_organizations',
        'app.dlv_users',
        'app.rcv_susers',
        'app.picking_plan_details',
        'app.assets',
        'app.asset_attributes',
        'app.asset_users',
        'app.instocks',
        'app.instock_plans',
        'app.instock_plan_details',
        'app.instock_plan_details_sts',
        'app.instock_plan_details_asset_type',
        'app.instock_plans_kbn',
        'app.instock_plans_sts',
        'app.instock_susers',
        'app.confirm_susers',
        'app.instock_details',
        'app.stock_histories',
        'app.stocktakes',
        'app.stocktake_susers',
        'app.stocktake_details',
        'app.instocks_instock_kbn',
        'app.instocks_asset_type',
        'app.repairs',
        'app.rentals',
        'app.stocks',
        'app.picking_susers',
        'app.delivery_companies',
        'app.abrogate_histories',
        'app.rental_histories',
        'app.repair_histories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PickingDetails') ? [] : ['className' => PickingDetailsTable::class];
        $this->PickingDetails = TableRegistry::get('PickingDetails', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PickingDetails);

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
