<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\BlogPublishingCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\BlogPublishingCategoriesTable Test Case
 */
class BlogPublishingCategoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\BlogPublishingCategoriesTable
     */
    public $BlogPublishingCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.BlogPublishingCategories',
        'app.BlogPosts',
        'app.PublishingCategories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('BlogPublishingCategories') ? [] : ['className' => BlogPublishingCategoriesTable::class];
        $this->BlogPublishingCategories = TableRegistry::getTableLocator()->get('BlogPublishingCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->BlogPublishingCategories);

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
