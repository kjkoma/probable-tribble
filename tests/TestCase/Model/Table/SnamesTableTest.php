<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SnamesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SnamesTable Test Case
 */
class SnamesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SnamesTable
     */
    public $Snames;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.snames'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Snames') ? [] : ['className' => SnamesTable::class];
        $this->Snames = TableRegistry::get('Snames', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Snames);

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
}
