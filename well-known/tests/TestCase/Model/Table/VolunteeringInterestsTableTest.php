<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VolunteeringInterestsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VolunteeringInterestsTable Test Case
 */
class VolunteeringInterestsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\VolunteeringInterestsTable
     */
    public $VolunteeringInterests;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.VolunteeringInterests',
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
        $config = TableRegistry::getTableLocator()->exists('VolunteeringInterests') ? [] : ['className' => VolunteeringInterestsTable::class];
        $this->VolunteeringInterests = TableRegistry::getTableLocator()->get('VolunteeringInterests', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->VolunteeringInterests);

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
