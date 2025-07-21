<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Resources Model
 *
 * @property \App\Model\Table\OrganizationsTable&\Cake\ORM\Association\BelongsTo $Organizations
 * @property \App\Model\Table\ResourceTypesTable&\Cake\ORM\Association\BelongsTo $ResourceTypes
 * @property \App\Model\Table\ResourceCategoriesTable&\Cake\ORM\Association\HasMany $ResourceCategories
 *
 * @method \App\Model\Entity\Resource get($primaryKey, $options = [])
 * @method \App\Model\Entity\Resource newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Resource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Resource|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Resource saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Resource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Resource[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Resource findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ResourcesTable extends Table
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

        $this->setTable('resources');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Translate', ['fields' => ['title', 'description']]);
        $this->addBehavior('UploadImage', [
            'fields' => [
                'file_link' => [
                    'input_file' => 'file',
                    'resize' => false,
                    'save_type' =>true,
                    'type_field' => 'file_type'
                ]
            ],
            'file_storage' => 'cloudinary'
        ]);
        $this->addBehavior('Elastic/ActivityLogger.Logger', [
            'logModel' => 'ActivityLogs',
            'scope' => [
                '\App',
            ],
        ]);

        $this->belongsTo('Organizations', [
            'foreignKey' => 'organization_id'
        ]);
        $this->belongsTo('Regions', [
            'foreignKey' => 'region_id'
        ]);
        $this->belongsTo('Countries', [
            'foreignKey' => 'country_id'
        ]);
        $this->belongsTo('ResourceTypes', [
            'foreignKey' => 'resource_type_id'
        ]);
        $this->belongsToMany('VolunteeringCategories', [
            'foreignKey' => 'resource_id',
            'targetForeignKey' => 'volunteering_category_id',
            'joinTable' => 'resource_categories'
        ]);
        $this->hasMany('ResourceFiles', [
            'foreignKey' => 'resource_id',
            'dependent' => true,
            'cascadeCallbacks' => true,
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
            ->scalar('file_type')
            ->maxLength('file_type', 15)
            ->allowEmptyString('file_type');

        $validator
            ->scalar('file_link')
            ->maxLength('file_link', 255)
            ->allowEmptyString('file_link');

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        $validator
            ->requirePresence('file', 'Create')
            ->allowEmptyFile('file', null, 'update')
            ->add('file', 'upload', [
                'rule' => ['uploadedFile', [
                    'types' => [
                        'image/jpeg', 'image/png',
                        'application/pdf', 'application/msword', 'application/vnd.ms-powerpoint', 
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'video/mp4',
                    ],
                ]],
                'on' => function ($context) {
                    return !empty($context['data']['file']);
                },
                'message' => __('The provided file type is invalid. Support file types are: {0}', ['jpeg, png, pdf, doc, docx, ppt, pptx and mp4'])
            ]);

        $validator
            ->add('file', 'file', [
                'rule' => ['fileSize', '<=', '99MB'],
                'on' => function ($context) {
                    return !empty($context['data']['file']) && $context['data']['file']['error'] !== UPLOAD_ERR_OK;
                },
                'message' => __('Image must be less than {0}', ['99MB'])
            ]);

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
        $rules->add($rules->existsIn(['region_id'], 'Regions'));
        $rules->add($rules->existsIn(['country_id'], 'Countries'));
        $rules->add($rules->existsIn(['resource_type_id'], 'ResourceTypes'));

        return $rules;
    }
}
