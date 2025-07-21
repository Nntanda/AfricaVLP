<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * VolunteeringCategories Model
 *
 * @property \App\Model\Table\EventCategoriesTable&\Cake\ORM\Association\HasMany $EventCategories
 * @property \App\Model\Table\NewsCategoriesTable&\Cake\ORM\Association\HasMany $NewsCategories
 * @property \App\Model\Table\OrganizationCategoriesTable&\Cake\ORM\Association\HasMany $OrganizationCategories
 *
 * @method \App\Model\Entity\VolunteeringCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\VolunteeringCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\VolunteeringCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\VolunteeringCategory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VolunteeringCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\VolunteeringCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\VolunteeringCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\VolunteeringCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class VolunteeringCategoriesTable extends Table
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

        $this->setTable('volunteering_categories');
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

        $this->hasMany('EventCategories', [
            'foreignKey' => 'volunteering_category_id'
        ]);
        $this->hasMany('NewsCategories', [
            'foreignKey' => 'volunteering_category_id'
        ]);
        $this->hasMany('OrganizationCategories', [
            'foreignKey' => 'volunteering_category_id'
        ]);
        $this->belongsToMany('VolunteeringOppurtunities', [
            'foreignKey' => 'volunteering_category_id',
            'targetForeignKey' => 'volunteering_oppurtunity_id',
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
            ->scalar('name')
            ->maxLength('name', 45)
            ->notEmptyString('name');

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        return $validator;
    }
}
