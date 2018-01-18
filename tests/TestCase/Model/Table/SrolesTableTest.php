<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SrolesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SrolesTable Test Case
 */
class SrolesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SrolesTable
     */
    public $Sroles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sroles',
        'app.srole_sauthorities',
        'app.suser_domains',
        'app.susers',
        'app.spasswords',
        'app.suser_sroles',
        'app.user_roles',
        'app.domains'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Sroles') ? [] : ['className' => SrolesTable::class];
        $this->Sroles = TableRegistry::get('Sroles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Sroles);

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
