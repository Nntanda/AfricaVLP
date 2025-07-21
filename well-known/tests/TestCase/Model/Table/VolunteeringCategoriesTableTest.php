<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\VolunteeringCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\VolunteeringCategoriesTable Test Case
 */
class VolunteeringCategoriesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\VolunteeringCategoriesTable
     */
    public $VolunteeringCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.VolunteeringCategories',
        'app.BlogCategories',
        'app.EventCategories',
        'app.NewsCategories',
        'app.OrganizationCategories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('VolunteeringCategories') ? [] : ['className' => VolunteeringCategoriesTable::class];
        $this->VolunteeringCategories = TableRegistry::getTableLocator()->get('VolunteeringCategories', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->VolunteeringCategories);

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
