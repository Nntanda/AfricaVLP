<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\AdminSupportsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\AdminSupportsTable Test Case
 */
class AdminSupportsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\AdminSupportsTable
     */
    public $AdminSupports;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AdminSupports',
        'app.Organizations',
        'app.AdminSupportMessages'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('AdminSupports') ? [] : ['className' => AdminSupportsTable::class];
        $this->AdminSupports = TableRegistry::getTableLocator()->get('AdminSupports', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->AdminSupports);

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
