<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TmpOrganizationUsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TmpOrganizationUsersTable Test Case
 */
class TmpOrganizationUsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TmpOrganizationUsersTable
     */
    public $TmpOrganizationUsers;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.TmpOrganizationUsers',
        'app.Organizations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('TmpOrganizationUsers') ? [] : ['className' => TmpOrganizationUsersTable::class];
        $this->TmpOrganizationUsers = TableRegistry::getTableLocator()->get('TmpOrganizationUsers', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TmpOrganizationUsers);

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
