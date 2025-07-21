<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ConversationParticipantsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ConversationParticipantsTable Test Case
 */
class ConversationParticipantsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ConversationParticipantsTable
     */
    public $ConversationParticipants;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.ConversationParticipants',
        'app.Conversations',
        'app.Organizations'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('ConversationParticipants') ? [] : ['className' => ConversationParticipantsTable::class];
        $this->ConversationParticipants = TableRegistry::getTableLocator()->get('ConversationParticipants', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ConversationParticipants);

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
