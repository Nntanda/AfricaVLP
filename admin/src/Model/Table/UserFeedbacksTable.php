<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * UserFeedbacks Model
 *
 * @property \App\Model\Table\ObjectsTable&\Cake\ORM\Association\BelongsTo $Objects
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\UserFeedback get($primaryKey, $options = [])
 * @method \App\Model\Entity\UserFeedback newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UserFeedback[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UserFeedback|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserFeedback saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UserFeedback patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UserFeedback[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UserFeedback findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UserFeedbacksTable extends Table
{
    public static $ratings = [
        ['value' => 1, 'text' => 'Poor', 'emoji' => '&#x1F621;'],
        ['value' => 2, 'text' => 'Ok', 'emoji' => '&#x1F610;'],
        ['value' => 3, 'text' => 'Good', 'emoji' => '&#x1F60A;'],
        ['value' => 4, 'text' => 'Excellent', 'emoji' => '&#x1F603;'],
        ['value' => 5, 'text' => 'Super', 'emoji' => '&#x1F60D;'],
    ];

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('user_feedbacks');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Objects', [
            'foreignKey' => 'object_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
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
            ->scalar('object_model')
            ->maxLength('object_model', 25)
            ->requirePresence('object_model', 'create')
            ->notEmptyString('object_model');

        $validator
            ->scalar('feedback_message')
            ->requirePresence('feedback_message', 'create')
            ->notEmptyString('feedback_message');

        $validator
            ->integer('feedback_rating')
            ->allowEmptyString('feedback_rating');

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
        $rules->add($rules->existsIn(['object_id'], 'Objects'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
