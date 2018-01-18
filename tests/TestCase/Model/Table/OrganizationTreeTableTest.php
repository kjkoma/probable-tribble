<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrganizationTreeTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrganizationTreeTable Test Case
 */
class OrganizationTreeTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\OrganizationTreeTable
     */
    public $OrganizationTree;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.organization_tree',
        'app.domains',
        'app.domain_apps',
        'app.sapps',
        'app.authorities',
        'app.categories',
        'app.classes',
        'app.companies',
        'app.customers',
        'app.product_models',
        'app.organizations',
        'app.users',
        'app.products',
        'app.role_authorities',
        'app.roles',
        'app.status_flows',
        'app.suser_domains',
        'app.susers',
        'app.sroles',
        'app.srole_sauthorities',
        'app.suser_sroles',
        'app.spasswords',
        'app.user_roles'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('OrganizationTree') ? [] : ['className' => OrganizationTreeTable::class];
        $this->OrganizationTree = TableRegistry::get('OrganizationTree', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->OrganizationTree);

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
