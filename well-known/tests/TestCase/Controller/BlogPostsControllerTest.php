<?php
namespace App\Test\TestCase\Controller;

use App\Controller\BlogPostsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\BlogPostsController Test Case
 *
 * @uses \App\Controller\BlogPostsController
 */
class BlogPostsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.BlogPosts',
        'app.BlogPostsTitleTranslation',
        'app.BlogPostsContentTranslation',
        'app.I18n',
        'app.Regions',
        'app.BlogPostComments',
        'app.VolunteeringCategories',
        'app.PublishingCategories',
        'app.BlogCategories',
        'app.BlogPublishingCategories'
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

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
