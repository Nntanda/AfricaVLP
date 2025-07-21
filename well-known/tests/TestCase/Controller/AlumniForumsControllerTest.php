<?php
namespace App\Test\TestCase\Controller;

use App\Controller\AlumniForumsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\AlumniForumsController Test Case
 *
 * @uses \App\Controller\AlumniForumsController
 */
class AlumniForumsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.AlumniForums'
    ];

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
