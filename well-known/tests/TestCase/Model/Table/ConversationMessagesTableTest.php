<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConversationMessagesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConversationMessagesTable Test Case
 */
class ConversationMessagesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ConversationMessagesTable
     */
    public $ConversationMessages;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ConversationMessages',
        'app.Conversations',
        'app.Organizations',
        'app.Users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ConversationMessages') ? [] : ['className' => ConversationMessagesTable::class];
        $this->ConversationMessages = TableRegistry::getTableLocator()->get('ConversationMessages', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ConversationMessages);

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
