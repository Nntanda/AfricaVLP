<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BlogPosts Model
 *
 * @property \App\Model\Table\RegionsTable&\Cake\ORM\Association\BelongsTo $Regions
 * @property \App\Model\Table\BlogCategoriesTable&\Cake\ORM\Association\HasMany $BlogCategories
 * @property \App\Model\Table\BlogPostCommentsTable&\Cake\ORM\Association\HasMany $BlogPostComments
 * @property \App\Model\Table\BlogPublishingCategoriesTable&\Cake\ORM\Association\HasMany $BlogPublishingCategories
 *
 * @method \App\Model\Entity\BlogPost get($primaryKey, $options = [])
 * @method \App\Model\Entity\BlogPost newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BlogPost[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BlogPost|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogPost saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogPost patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPost[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPost findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BlogPostsTable extends Table
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

        $this->setTable('blog_posts');
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

        $this->belongsTo('Regions', [
            'foreignKey' => 'region_id'
        ]);
        $this->hasMany('BlogCategories', [
            'foreignKey' => 'blog_post_id'
        ]);
        $this->hasMany('BlogPostComments', [
            'foreignKey' => 'blog_post_id'
        ]);
        $this->hasMany('BlogPublishingCategories', [
            'foreignKey' => 'blog_post_id'
        ]);
        $this->belongsToMany('PublishingCategories', [
            'foreignKey' => 'blog_post_id',
            'targetForeignKey' => 'publishing_category_id',
            'joinTable' => 'blog_publishing_categories'
        ]);
        $this->belongsToMany('VolunteeringCategories', [
            'foreignKey' => 'blog_post_id',
            'targetForeignKey' => 'volunteering_category_id',
            'joinTable' => 'blog_categories'
        ]);
        $this->belongsToMany('Tags', [
            'foreignKey' => 'blog_post_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'blog_posts_tags',
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
            ->allowEmptyString('title');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 255)
            ->allowEmptyString('slug');

        $validator
            ->scalar('content')
            ->maxLength('content', 4294967295)
            ->allowEmptyString('content');

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
        $rules->add($rules->existsIn(['region_id'], 'Regions'));

        return $rules;
    }
}
