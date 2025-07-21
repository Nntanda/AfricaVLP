<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BlogPostCommentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BlogPostCommentsTable Test Case
 */
class BlogPostCommentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BlogPostCommentsTable
     */
    public $BlogPostComments;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.BlogPostComments',
        'app.Users',
        'app.BlogPosts'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('BlogPostComments') ? [] : ['className' => BlogPostCommentsTable::class];
        $this->BlogPostComments = TableRegistry::getTableLocator()->get('BlogPostComments', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BlogPostComments);

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
