<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Events Model
 *
 * @property \App\Model\Table\OrganizationsTable&\Cake\ORM\Association\BelongsTo $Organizations
 * @property \App\Model\Table\CountriesTable&\Cake\ORM\Association\BelongsTo $Countries
 * @property \App\Model\Table\CitiesTable&\Cake\ORM\Association\BelongsTo $Cities
 * @property \App\Model\Table\RegionsTable&\Cake\ORM\Association\BelongsTo $Regions
 * @property \App\Model\Table\EventCommentsTable&\Cake\ORM\Association\HasMany $EventComments
 * @property \App\Model\Table\VolunteeringOppurtunitiesTable&\Cake\ORM\Association\HasMany $VolunteeringOppurtunities
 *
 * @method \App\Model\Entity\Event get($primaryKey, $options = [])
 * @method \App\Model\Entity\Event newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Event[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Event|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Event saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Event patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Event[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Event findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class EventsTable extends Table
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

        $this->setTable('events');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AdminNotification');
        $this->addBehavior('Translate', ['fields' => ['title', 'description']]);
        $this->addBehavior('UploadImage', [
            'fields' => [
                'image' => [
                    'input_file' => 'file',
                    'resize' => false,
                    'save_type' =>false,
                ]
            ],
            'file_storage' => 'cloudinary'
        ]);

        $this->belongsTo('Organizations', [
            'foreignKey' => 'organization_id'
        ]);
        $this->belongsTo('Countries', [
            'foreignKey' => 'country_id'
        ]);
        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id'
        ]);
        $this->belongsTo('Regions', [
            'foreignKey' => 'region_id'
        ]);
        $this->hasMany('EventComments', [
            'foreignKey' => 'event_id'
        ]);
        $this->hasMany('VolunteeringOppurtunities', [
            'foreignKey' => 'event_id'
        ]);
        $this->belongsToMany('VolunteeringCategories', [
            'foreignKey' => 'event_id',
            'targetForeignKey' => 'volunteering_category_id',
            'joinTable' => 'events_categories'
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
            ->scalar('title')
            ->maxLength('title', 500)
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->maxLength('description', 1000)
            ->notEmptyString('description');

        $validator
            ->scalar('address')
            ->notEmptyString('address');

        $validator
            // ->dateTime('start_date')
            ->date('start_date')
            ->notEmptyDateTime('start_date');

        $validator
            // ->dateTime('end_date')
            ->date('end_date')
            ->notEmptyDateTime('end_date');

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        $validator
            ->allowEmptyString('requesting_volunteers');

        $validator
            ->allowEmptyString('has_remunerations');
        
        $validator
            ->allowEmptyFile('file')
            ->add('file', 'upload', [
                'rule' => ['uploadedFile', [
                    'types' => [
                        'image/jpeg', 'image/png',
                    ],
                ]],
                'on' => function ($context) {
                    return !empty($context['data']['file']);
                },
                'message' => __('The provided file type is invalid. Support file types are: {0}', ['jpeg and png'])
            ]);

        $validator
            ->add('file', 'file', [
                'rule' => ['fileSize', '<=', '1MB'],
                'on' => function ($context) {
                    return !empty($context['data']['file']) && $context['data']['file']['error'] !== UPLOAD_ERR_OK;
                },
                'message' => __('Image must be less than {0}', ['1MB'])
            ]);

        $validator
            ->scalar('url')
            ->maxLength('url', 1000)
            ->allowEmptyString('url');

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
        $rules->add($rules->existsIn(['organization_id'], 'Organizations'));
        $rules->add($rules->existsIn(['country_id'], 'Countries'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));
        $rules->add($rules->existsIn(['region_id'], 'Regions'));

        return $rules;
    }
}
