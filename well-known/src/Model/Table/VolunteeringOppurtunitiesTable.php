<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VolunteeringOppurtunities Model
 *
 * @property \App\Model\Table\EventsTable&\Cake\ORM\Association\BelongsTo $Events
 * @property \App\Model\Table\VolunteeringDurationsTable&\Cake\ORM\Association\BelongsTo $VolunteeringDurations
 * @property \App\Model\Table\VolunteeringRolesTable&\Cake\ORM\Association\BelongsTo $VolunteeringRoles
 * @property \App\Model\Table\EventCategoriesTable&\Cake\ORM\Association\HasMany $EventCategories
 * @property \App\Model\Table\VolunteeringHistoriesTable&\Cake\ORM\Association\HasMany $VolunteeringHistories
 * @property \App\Model\Table\VolunteeringInterestsTable&\Cake\ORM\Association\HasMany $VolunteeringInterests
 *
 * @method \App\Model\Entity\VolunteeringOppurtunity get($primaryKey, $options = [])
 * @method \App\Model\Entity\VolunteeringOppurtunity newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VolunteeringOppurtunity[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VolunteeringOppurtunity|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VolunteeringOppurtunity saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VolunteeringOppurtunity patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VolunteeringOppurtunity[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VolunteeringOppurtunity findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VolunteeringOppurtunitiesTable extends Table
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

        $this->setTable('volunteering_oppurtunities');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Events', [
            'foreignKey' => 'event_id'
        ]);
        $this->belongsTo('VolunteeringDurations', [
            'foreignKey' => 'volunteering_duration_id'
        ]);
        $this->belongsTo('VolunteeringRoles', [
            'foreignKey' => 'volunteering_role_id'
        ]);
        $this->hasMany('VolunteeringHistories', [
            'foreignKey' => 'volunteering_oppurtunity_id'
        ]);
        $this->hasMany('VolunteeringInterests', [
            'foreignKey' => 'volunteering_oppurtunity_id'
        ]);
        $this->belongsToMany('VolunteeringCategories', [
            'foreignKey' => 'volunteering_oppurtunity_id',
            'targetForeignKey' => 'volunteering_category_id',
            'joinTable' => 'event_categories'
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

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['event_id'], 'Events'));
        $rules->add($rules->existsIn(['volunteering_duration_id'], 'VolunteeringDurations'));
        $rules->add($rules->existsIn(['volunteering_role_id'], 'VolunteeringRoles'));

        return $rules;
    }
}
