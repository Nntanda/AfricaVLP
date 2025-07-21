<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VolunteeringHistoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VolunteeringHistoriesTable Test Case
 */
class VolunteeringHistoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\VolunteeringHistoriesTable
     */
    public $VolunteeringHistories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.VolunteeringHistories',
        'app.Organizations',
        'app.Users',
        'app.VolunteeringOppurtunities'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('VolunteeringHistories') ? [] : ['className' => VolunteeringHistoriesTable::class];
        $this->VolunteeringHistories = TableRegistry::getTableLocator()->get('VolunteeringHistories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->VolunteeringHistories);

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
