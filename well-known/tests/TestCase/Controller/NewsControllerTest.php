<?php
namespace App\Test\TestCase\Controller;

use App\Controller\NewsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\NewsController Test Case
 *
 * @uses \App\Controller\NewsController
 */
class NewsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.News',
        'app.NewsTitleTranslation',
        'app.NewsContentTranslation',
        'app.I18n',
        'app.Organizations',
        'app.Regions',
        'app.NewsComments',
        'app.VolunteeringCategories',
        'app.PublishingCategories',
        'app.NewsCategories',
        'app.NewsPublishingCategories'
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

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
