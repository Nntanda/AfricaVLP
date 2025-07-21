<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BlogPostsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BlogPostsTable Test Case
 */
class BlogPostsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BlogPostsTable
     */
    public $BlogPosts;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.BlogPosts',
        'app.Regions',
        'app.BlogCategories',
        'app.BlogPostComments',
        'app.BlogPublishingCategories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('BlogPosts') ? [] : ['className' => BlogPostsTable::class];
        $this->BlogPosts = TableRegistry::getTableLocator()->get('BlogPosts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BlogPosts);

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
