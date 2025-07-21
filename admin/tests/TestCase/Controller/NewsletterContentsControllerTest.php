<?php
namespace App\Test\TestCase\Controller;

use App\Controller\NewsletterContentsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\NewsletterContentsController Test Case
 *
 * @uses \App\Controller\NewsletterContentsController
 */
class NewsletterContentsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.NewsletterContents'
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
