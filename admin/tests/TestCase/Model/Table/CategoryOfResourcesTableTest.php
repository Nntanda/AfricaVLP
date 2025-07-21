<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\CategoryOfResourcesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\CategoryOfResourcesTable Test Case
 */
class CategoryOfResourcesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\CategoryOfResourcesTable
     */
    public $CategoryOfResources;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.CategoryOfResources',
        'app.ResourceCategories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('CategoryOfResources') ? [] : ['className' => CategoryOfResourcesTable::class];
        $this->CategoryOfResources = TableRegistry::getTableLocator()->get('CategoryOfResources', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CategoryOfResources);

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
