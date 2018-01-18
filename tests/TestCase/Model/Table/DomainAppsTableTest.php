<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\DomainAppsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\DomainAppsTable Test Case
 */
class DomainAppsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\DomainAppsTable
     */
    public $DomainApps;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.domain_apps',
        'app.domains',
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
        'app.users',
        'app.sapps'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('DomainApps') ? [] : ['className' => DomainAppsTable::class];
        $this->DomainApps = TableRegistry::get('DomainApps', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->DomainApps);

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
