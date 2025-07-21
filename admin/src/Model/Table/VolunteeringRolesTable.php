<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VolunteeringRoles Model
 *
 * @property \App\Model\Table\VolunteeringOppurtunitiesTable&\Cake\ORM\Association\HasMany $VolunteeringOppurtunities
 *
 * @method \App\Model\Entity\VolunteeringRole get($primaryKey, $options = [])
 * @method \App\Model\Entity\VolunteeringRole newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VolunteeringRole[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VolunteeringRole|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VolunteeringRole saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VolunteeringRole patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VolunteeringRole[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VolunteeringRole findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VolunteeringRolesTable extends Table
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

        $this->setTable('volunteering_roles');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Translate', ['fields' => ['name']]);
        $this->addBehavior('Elastic/ActivityLogger.Logger', [
            'logModel' => 'ActivityLogs',
            'scope' => [
                '\App',
            ],
        ]);

        $this->hasMany('VolunteeringOppurtunities', [
            'foreignKey' => 'volunteering_role_id'
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
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        return $validator;
    }
}
