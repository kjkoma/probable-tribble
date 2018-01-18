<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SpasswordsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SpasswordsTable Test Case
 */
class SpasswordsTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\SpasswordsTable
     */
    public $Spasswords;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.spasswords',
        'app.susers',
        'app.suser_sroles',
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
        $config = TableRegistry::exists('Spasswords') ? [] : ['className' => SpasswordsTable::class];
        $this->Spasswords = TableRegistry::get('Spasswords', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Spasswords);

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
