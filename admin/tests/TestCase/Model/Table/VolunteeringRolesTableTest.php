<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VolunteeringRolesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VolunteeringRolesTable Test Case
 */
class VolunteeringRolesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\VolunteeringRolesTable
     */
    public $VolunteeringRoles;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.VolunteeringRoles',
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
        $config = TableRegistry::getTableLocator()->exists('VolunteeringRoles') ? [] : ['className' => VolunteeringRolesTable::class];
        $this->VolunteeringRoles = TableRegistry::getTableLocator()->get('VolunteeringRoles', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->VolunteeringRoles);

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
