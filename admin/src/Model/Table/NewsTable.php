<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * News Model
 *
 * @property \App\Model\Table\OrganizationsTable&\Cake\ORM\Association\BelongsTo $Organizations
 * @property \App\Model\Table\RegionsTable&\Cake\ORM\Association\BelongsTo $Regions
 * @property \App\Model\Table\NewsCategoriesTable&\Cake\ORM\Association\HasMany $NewsCategories
 * @property \App\Model\Table\PublishingCategoriesTable&\Cake\ORM\Association\BelongsToMany $PublishingCategories
 *
 * @method \App\Model\Entity\News get($primaryKey, $options = [])
 * @method \App\Model\Entity\News newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\News[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\News|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\News saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\News patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\News[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\News findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NewsTable extends Table
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

        $this->setTable('news');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tags');
        $this->addBehavior('Translate', ['fields' => ['title', 'content']]);
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
        $this->hasMany('NewsCategories', [
            'foreignKey' => 'news_id'
        ]);
        $this->belongsToMany('PublishingCategories', [
            'foreignKey' => 'news_id',
            'targetForeignKey' => 'publishing_category_id',
            'joinTable' => 'news_publishing_categories'
        ]);
        $this->belongsToMany('VolunteeringCategories', [
            'foreignKey' => 'news_id',
            'targetForeignKey' => 'volunteering_category_id',
            'joinTable' => 'news_categories'
        ]);
        $this->belongsToMany('Tags', [
            'foreignKey' => 'news_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'news_tags',
            'dependent' => true
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
            ->scalar('slug')
            ->maxLength('slug', 255)
            ->allowEmptyString('slug');

        $validator
            ->scalar('content')
            ->notEmptyString('content');

        $validator
            ->integer('status')
            ->allowEmptyString('status');
        
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

        return $rules;
    }
}
