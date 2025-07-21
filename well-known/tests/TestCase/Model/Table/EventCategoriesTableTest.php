<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EventCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EventCategoriesTable Test Case
 */
class EventCategoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EventCategoriesTable
     */
    public $EventCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.EventCategories',
        'app.VolunteeringOppurtunities',
        'app.VolunteeringCategories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('EventCategories') ? [] : ['className' => EventCategoriesTable::class];
        $this->EventCategories = TableRegistry::getTableLocator()->get('EventCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->EventCategories);

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
