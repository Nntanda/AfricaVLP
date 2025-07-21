<?php
namespace App\Test\TestCase\Model\Behavior;

use App\Model\Behavior\TagsBehavior;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Behavior\TagsBehavior Test Case
 */
class TagsBehaviorTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Behavior\TagsBehavior
     */
    public $Tags;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->Tags = new TagsBehavior();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Tags);

        parent::tearDown();
    }

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
