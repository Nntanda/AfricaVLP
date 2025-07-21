<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\InstitutionTypesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\InstitutionTypesTable Test Case
 */
class InstitutionTypesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\InstitutionTypesTable
     */
    public $InstitutionTypes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.InstitutionTypes',
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
        $config = TableRegistry::getTableLocator()->exists('InstitutionTypes') ? [] : ['className' => InstitutionTypesTable::class];
        $this->InstitutionTypes = TableRegistry::getTableLocator()->get('InstitutionTypes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->InstitutionTypes);

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
