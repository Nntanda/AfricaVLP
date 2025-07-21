<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WidgetsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WidgetsTable Test Case
 */
class WidgetsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\WidgetsTable
     */
    public $Widgets;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Widgets'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Widgets') ? [] : ['className' => WidgetsTable::class];
        $this->Widgets = TableRegistry::getTableLocator()->get('Widgets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Widgets);

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
