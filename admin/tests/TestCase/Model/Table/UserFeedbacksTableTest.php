<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UserFeedbacksTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UserFeedbacksTable Test Case
 */
class UserFeedbacksTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UserFeedbacksTable
     */
    public $UserFeedbacks;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.UserFeedbacks',
        'app.Objects',
        'app.Users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('UserFeedbacks') ? [] : ['className' => UserFeedbacksTable::class];
        $this->UserFeedbacks = TableRegistry::getTableLocator()->get('UserFeedbacks', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->UserFeedbacks);

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
