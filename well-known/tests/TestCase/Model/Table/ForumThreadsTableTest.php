<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ForumThreadsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ForumThreadsTable Test Case
 */
class ForumThreadsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ForumThreadsTable
     */
    public $ForumThreads;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ForumThreads',
        'app.Organizations',
        'app.Users',
        'app.ForumPosts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ForumThreads') ? [] : ['className' => ForumThreadsTable::class];
        $this->ForumThreads = TableRegistry::getTableLocator()->get('ForumThreads', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ForumThreads);

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
