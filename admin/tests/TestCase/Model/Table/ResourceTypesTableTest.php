<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ResourceTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ResourceTypesTable Test Case
 */
class ResourceTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ResourceTypesTable
     */
    public $ResourceTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ResourceTypes',
        'app.Resources'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ResourceTypes') ? [] : ['className' => ResourceTypesTable::class];
        $this->ResourceTypes = TableRegistry::getTableLocator()->get('ResourceTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ResourceTypes);

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
