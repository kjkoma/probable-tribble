<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SappsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SappsTable Test Case
 */
class SappsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SappsTable
     */
    public $Sapps;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.sapps',
        'app.apps'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Sapps') ? [] : ['className' => SappsTable::class];
        $this->Sapps = TableRegistry::get('Sapps', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Sapps);

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
