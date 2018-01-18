<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ClassTreeTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ClassTreeTable Test Case
 */
class ClassTreeTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\ClassTreeTable
     */
    public $ClassTree;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.class_tree',
        'app.domains',
        'app.domain_apps',
        'app.sapps',
        'app.authorities',
        'app.categories',
        'app.classifications',
        'app.products',
        'app.companies',
        'app.customers',
        'app.organization_tree',
        'app.ancestor',
        'app.users',
        'app.organizations',
        'app.ancestor_tree',
        'app.descendant',
        'app.descendant_tree',
        'app.product_models',
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
        $config = TableRegistry::exists('ClassTree') ? [] : ['className' => ClassTreeTable::class];
        $this->ClassTree = TableRegistry::get('ClassTree', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ClassTree);

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
