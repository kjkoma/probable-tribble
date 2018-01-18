<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DomainsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DomainsTable Test Case
 */
class DomainsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DomainsTable
     */
    public $Domains;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
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
        'app.suser_domains',
        'app.susers',
        'app.spasswords',
        'app.suser_sroles',
        'app.user_roles',
        'app.sroles',
        'app.srole_sauthorities',
        'app.users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Domains') ? [] : ['className' => DomainsTable::class];
        $this->Domains = TableRegistry::get('Domains', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Domains);

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
