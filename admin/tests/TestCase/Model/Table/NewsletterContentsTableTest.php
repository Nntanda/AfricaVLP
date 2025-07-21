<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\NewsletterContentsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\NewsletterContentsTable Test Case
 */
class NewsletterContentsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\NewsletterContentsTable
     */
    public $NewsletterContents;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.NewsletterContents',
        'app.Objects'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('NewsletterContents') ? [] : ['className' => NewsletterContentsTable::class];
        $this->NewsletterContents = TableRegistry::getTableLocator()->get('NewsletterContents', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->NewsletterContents);

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
