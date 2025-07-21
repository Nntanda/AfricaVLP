<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VolunteeringInterests Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\VolunteeringOppurtunitiesTable&\Cake\ORM\Association\BelongsTo $VolunteeringOppurtunities
 *
 * @method \App\Model\Entity\VolunteeringInterest get($primaryKey, $options = [])
 * @method \App\Model\Entity\VolunteeringInterest newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VolunteeringInterest[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VolunteeringInterest|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VolunteeringInterest saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VolunteeringInterest patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VolunteeringInterest[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VolunteeringInterest findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VolunteeringInterestsTable extends Table
{
    const PRE_EVENT = 1;
    const POST_EVENT = 2;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('volunteering_interests');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsTo('VolunteeringOppurtunities', [
            'foreignKey' => 'volunteering_oppurtunity_id'
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
            ->integer('type')
            ->allowEmptyString('type');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['volunteering_oppurtunity_id'], 'VolunteeringOppurtunities'));

        return $rules;
    }
}
