<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SuserDomainsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SuserDomainsTable Test Case
 */
class SuserDomainsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SuserDomainsTable
     */
    public $SuserDomains;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.suser_domains',
        'app.susers',
        'app.spasswords',
        'app.suser_sroles',
        'app.user_roles',
        'app.domains',
        'app.apps',
        'app.authorities',
        'app.categories',
        'app.classes',
        'app.companies',
        'app.customers',
        'app.models',
        'app.organizations',
        'app.products',
        'app.role_authorities',
        'app.roles',
        'app.status_flows',
        'app.users',
        'app.sroles',
        'app.srole_sauthorities'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('SuserDomains') ? [] : ['className' => SuserDomainsTable::class];
        $this->SuserDomains = TableRegistry::get('SuserDomains', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SuserDomains);

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
