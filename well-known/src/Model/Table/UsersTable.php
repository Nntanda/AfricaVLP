<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Utility\Hash;

/**
 * Users Model
 *
 * @property \App\Model\Table\CountriesTable&\Cake\ORM\Association\BelongsTo $Countries
 * @property \App\Model\Table\CitiesTable&\Cake\ORM\Association\BelongsTo $Cities
 * @property \App\Model\Table\BlogPostCommentsTable&\Cake\ORM\Association\HasMany $BlogPostComments
 * @property \App\Model\Table\ConversationMessagesTable&\Cake\ORM\Association\HasMany $ConversationMessages
 * @property \App\Model\Table\EventCommentsTable&\Cake\ORM\Association\HasMany $EventComments
 * @property \App\Model\Table\ForumPostsTable&\Cake\ORM\Association\HasMany $ForumPosts
 * @property \App\Model\Table\ForumThreadsTable&\Cake\ORM\Association\HasMany $ForumThreads
 * @property \App\Model\Table\NewsCommentsTable&\Cake\ORM\Association\HasMany $NewsComments
 * @property \App\Model\Table\OrganizationAlumniTable&\Cake\ORM\Association\HasMany $OrganizationAlumni
 * @property \App\Model\Table\OrganizationUsersTable&\Cake\ORM\Association\HasMany $OrganizationUsers
 * @property \App\Model\Table\OrganizationsTable&\Cake\ORM\Association\HasMany $Organizations
 * @property \App\Model\Table\VolunteeringHistoriesTable&\Cake\ORM\Association\HasMany $VolunteeringHistories
 * @property \App\Model\Table\VolunteeringInterestsTable&\Cake\ORM\Association\HasMany $VolunteeringInterests
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    CONST ACCOUNT_CREATED = 1;
    CONST PROFILE_CREATED = 2;
    CONST PROFILE_COMPLETED = 3;
    
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Register');
        $this->addBehavior('Password');
        $this->addBehavior('UploadImage', [
            'fields' => [
                'profile_image' => [
                    'input_file' => 'file',
                    'resize' => false,
                    'save_type' =>false,
                ]
            ],
            'file_storage' => 'cloudinary'
        ]);

        $this->belongsTo('Countries', [
            'foreignKey' => 'resident_country_id'
        ]);
        $this->belongsTo('BirthCountry', [
            'className' => 'Countries',
            'foreignKey' => 'nationality_at_birth'
        ]);
        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id'
        ]);
        $this->hasMany('BlogPostComments', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ConversationMessages', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('EventComments', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ForumPosts', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('ForumThreads', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('NewsComments', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('OrganizationAlumni', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('OrganizationUsers', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Organizations', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('VolunteeringHistories', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('VolunteeringInterests', [
            'foreignKey' => 'user_id'
        ]);
        $this->belongsToMany('PlatformInterests', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'platform_interest_id',
            'joinTable' => 'users_platform_interests'
        ]);
        $this->belongsToMany('VolunteeringCategories', [
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'volunteering_category_id',
            'joinTable' => 'users_volunteering_categories'
        ]);
    }


    /**
     * Adds some rules for password confirm
     * @param Validator $validator Cake validator object.
     * @return Validator
     */
    public function validationPasswordConfirm(Validator $validator)
    {
        $validator
            ->requirePresence('confirm_password', 'create')
            ->notEmptyString('confirm_password');

        $validator->add('password', 'custom', [
            'rule' => function ($value, $context) {
                $confirm = Hash::get($context, 'data.confirm_password');
                if (!is_null($confirm) && $value != $confirm) {
                    return false;
                }

                return true;
            },
            'message' => __('Your password does not match your confirm password'),
            'on' => ['create', 'update'],
            'allowEmpty' => false
        ]);

        return $validator;
    }

    /**
     * Adds rules for current password
     *
     * @param Validator $validator Cake validator object.
     * @return Validator
     */
    public function validationCurrentPassword(Validator $validator)
    {
        $validator
            ->notEmptyString('current_password');

        return $validator;
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
            ->scalar('first_name')
            ->maxLength('first_name', 45)
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 45)
            ->requirePresence('last_name', 'create')
            ->notEmptyString('last_name');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('password')
            ->requirePresence('password', 'create')
            ->maxLength('password', 255)
            ->notEmptyString('password');

        $validator
            ->scalar('phone_number')
            ->maxLength('phone_number', 15)
            ->allowEmptyString('phone_number');

        $validator
            ->scalar('language')
            ->maxLength('language', 45)
            ->allowEmptyString('language');

        $validator
            ->scalar('profile_image')
            ->maxLength('profile_image', 255)
            ->allowEmptyFile('profile_image');

        $validator
            ->scalar('token')
            ->maxLength('token', 255)
            ->allowEmptyString('token');

        $validator
            ->scalar('Gender')
            ->maxLength('Gender', 10)
            ->requirePresence('Gender', 'create')
            ->notEmptyString('Gender');

        $validator
            ->date('Date_of_Birth')
            ->requirePresence('Date_of_Birth', 'create')
            ->notEmptyDate('Date_of_Birth');

        $validator
            ->scalar('place_of_birth')
            ->maxLength('place_of_birth', 45)
            ->allowEmptyString('place_of_birth');

        $validator
            ->integer('nationality_at_birth')
            ->allowEmptyString('nationality_at_birth');

        $validator
            ->integer('current_nationality')
            ->allowEmptyString('current_nationality');

        $validator
            ->scalar('marital_status')
            ->maxLength('marital_status', 15)
            ->allowEmptyString('marital_status');

        $validator
            ->scalar('current_address')
            ->maxLength('current_address', 255)
            ->allowEmptyString('current_address');

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
     * Wrapper for all validation rules for register
     * @param Validator $validator Cake validator object.
     *
     * @return Validator
     */
    public function validationRegister(Validator $validator)
    {
        $validator = $this->validationDefault($validator);
        $validator = $this->validationPasswordConfirm($validator);

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
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['resident_country_id'], 'Countries'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));

        return $rules;
    }
}
