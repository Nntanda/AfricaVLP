<?php
namespace App\Test\TestCase\Controller;

use App\Controller\OrganizationsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\OrganizationsController Test Case
 *
 * @uses \App\Controller\OrganizationsController
 */
class OrganizationsControllerTest extends TestCase
{
    use IntegrationTestTrait;

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
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
