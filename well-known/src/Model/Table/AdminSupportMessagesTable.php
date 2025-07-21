<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AdminSupportMessages Model
 *
 * @property \App\Model\Table\AdminSupportsTable&\Cake\ORM\Association\BelongsTo $AdminSupports
 * @property \App\Model\Table\SenderUsersTable&\Cake\ORM\Association\BelongsTo $SenderUsers
 *
 * @method \App\Model\Entity\AdminSupportMessage get($primaryKey, $options = [])
 * @method \App\Model\Entity\AdminSupportMessage newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AdminSupportMessage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AdminSupportMessage|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdminSupportMessage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AdminSupportMessage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AdminSupportMessage[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AdminSupportMessage findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AdminSupportMessagesTable extends Table
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

        $this->setTable('admin_support_messages');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('AdminSupports', [
            'foreignKey' => 'admin_support_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('SenderUsers', [
            'className' => 'Users',
            'foreignKey' => 'sender_user_id',
            // 'joinType' => 'INNER'
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
            ->scalar('message')
            ->allowEmptyString('message');

        $validator
            ->scalar('sender')
            ->maxLength('sender', 15)
            ->requirePresence('sender', 'create')
            ->notEmptyString('sender');

        $validator
            ->dateTime('time')
            ->allowEmptyDateTime('time');

        $validator
            ->allowEmptyString('is_read');

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['admin_support_id'], 'AdminSupports'));
        $rules->add($rules->existsIn(['sender_user_id'], 'SenderUsers'));

        return $rules;
    }
}
