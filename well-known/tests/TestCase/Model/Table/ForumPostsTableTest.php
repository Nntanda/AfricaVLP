<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ForumPostsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ForumPostsTable Test Case
 */
class ForumPostsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ForumPostsTable
     */
    public $ForumPosts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ForumPosts',
        'app.ForumThreads',
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
        $config = TableRegistry::getTableLocator()->exists('ForumPosts') ? [] : ['className' => ForumPostsTable::class];
        $this->ForumPosts = TableRegistry::getTableLocator()->get('ForumPosts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ForumPosts);

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
