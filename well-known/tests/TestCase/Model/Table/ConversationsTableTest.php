<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConversationsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConversationsTable Test Case
 */
class ConversationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ConversationsTable
     */
    public $Conversations;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Conversations',
        'app.ConversationMessages',
        'app.ConversationParticipants'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Conversations') ? [] : ['className' => ConversationsTable::class];
        $this->Conversations = TableRegistry::getTableLocator()->get('Conversations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Conversations);

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
