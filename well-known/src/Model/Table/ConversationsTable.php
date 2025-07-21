<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Conversations Model
 *
 * @property \App\Model\Table\ConversationMessagesTable&\Cake\ORM\Association\HasMany $ConversationMessages
 * @property \App\Model\Table\ConversationParticipantsTable&\Cake\ORM\Association\HasMany $ConversationParticipants
 *
 * @method \App\Model\Entity\Conversation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Conversation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Conversation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Conversation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Conversation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Conversation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Conversation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Conversation findOrCreate($search, callable $callback = null, $options = [])
 */
class ConversationsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('conversations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('ConversationMessages', [
            'foreignKey' => 'conversation_id'
        ]);
        $this->hasMany('ConversationParticipants', [
            'foreignKey' => 'conversation_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        return $validator;
    }
}
