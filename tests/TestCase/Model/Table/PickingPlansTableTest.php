<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\PickingPlansTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\PickingPlansTable Test Case
 */
class PickingPlansTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\PickingPlansTable
     */
    public $PickingPlans;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.picking_plans',
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
        'app.req_organizations',
        'app.req_users',
        'app.dlv_organizations',
        'app.dlv_users',
        'app.rcv_susers',
        'app.picking_plan_details',
        'app.pickings'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('PickingPlans') ? [] : ['className' => PickingPlansTable::class];
        $this->PickingPlans = TableRegistry::get('PickingPlans', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->PickingPlans);

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
