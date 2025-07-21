<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\OrganizationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\OrganizationsTable Test Case
 */
class OrganizationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\OrganizationsTable
     */
    public $Organizations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Organizations',
        'app.OrganizationTypes',
        'app.Countries',
        'app.Cities',
        'app.InstitutionTypes',
        'app.CategoryOfOrganizations',
        'app.Users',
        'app.AdminSupports',
        'app.ConversationMessages',
        'app.ConversationParticipants',
        'app.Events',
        'app.ForumThreads',
        'app.News',
        'app.OrganizationAlumni',
        'app.OrganizationCategories',
        'app.OrganizationOffices',
        'app.OrganizationUsers',
        'app.Resources',
        'app.VolunteeringHistories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Organizations') ? [] : ['className' => OrganizationsTable::class];
        $this->Organizations = TableRegistry::getTableLocator()->get('Organizations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Organizations);

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
