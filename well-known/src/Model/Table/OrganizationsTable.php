<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use App\Email\EmailSender;

/**
 * Organizations Model
 *
 * @property \App\Model\Table\OrganizationTypesTable&\Cake\ORM\Association\BelongsTo $OrganizationTypes
 * @property \App\Model\Table\CountriesTable&\Cake\ORM\Association\BelongsTo $Countries
 * @property \App\Model\Table\CitiesTable&\Cake\ORM\Association\BelongsTo $Cities
 * @property \App\Model\Table\InstitutionTypesTable&\Cake\ORM\Association\BelongsTo $InstitutionTypes
 * @property \App\Model\Table\CategoryOfOrganizationsTable&\Cake\ORM\Association\BelongsTo $CategoryOfOrganizations
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\AdminSupportsTable&\Cake\ORM\Association\HasMany $AdminSupports
 * @property \App\Model\Table\ConversationMessagesTable&\Cake\ORM\Association\HasMany $ConversationMessages
 * @property \App\Model\Table\ConversationParticipantsTable&\Cake\ORM\Association\HasMany $ConversationParticipants
 * @property \App\Model\Table\EventsTable&\Cake\ORM\Association\HasMany $Events
 * @property \App\Model\Table\ForumThreadsTable&\Cake\ORM\Association\HasMany $ForumThreads
 * @property \App\Model\Table\NewsTable&\Cake\ORM\Association\HasMany $News
 * @property \App\Model\Table\OrganizationAlumniTable&\Cake\ORM\Association\HasMany $OrganizationAlumni
 * @property \App\Model\Table\OrganizationCategoriesTable&\Cake\ORM\Association\HasMany $OrganizationCategories
 * @property \App\Model\Table\OrganizationOfficesTable&\Cake\ORM\Association\HasMany $OrganizationOffices
 * @property \App\Model\Table\OrganizationUsersTable&\Cake\ORM\Association\HasMany $OrganizationUsers
 * @property \App\Model\Table\ResourcesTable&\Cake\ORM\Association\HasMany $Resources
 * @property \App\Model\Table\VolunteeringHistoriesTable&\Cake\ORM\Association\HasMany $VolunteeringHistories
 *
 * @method \App\Model\Entity\Organization get($primaryKey, $options = [])
 * @method \App\Model\Entity\Organization newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Organization[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Organization|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Organization saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Organization patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Organization[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Organization findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrganizationsTable extends Table
{
    // const VOLUNTEERING_ORG = 1;
    const NON_GOVERNMENT_ORG = 1;
    const GOVERNMENT_ORG = 2;
    const INTERNATIONAL_ORG = 3;
    const TRADITIONAL_ORG = 4;
    const UNIVERSITY_ORG = 5;
    const OTHER_ORG = 6;

    const TYPES_LIST = [
        SELF::GOVERNMENT_ORG => 'National Volunteer Program (Governmental)',
        SELF::NON_GOVERNMENT_ORG => 'Non-governmental (Civil Society, Grassroot)',
        SELF::INTERNATIONAL_ORG => 'International Volunteer Organization',
        SELF::TRADITIONAL_ORG => 'Traditional Volunteerism',
        SELF::UNIVERSITY_ORG => 'University Volunteer Program',
        SELF::OTHER_ORG => 'Other',
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

        $this->setTable('organizations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('AdminNotification');
        $this->addBehavior('UploadImage', [
            'fields' => [
                'logo' => [
                    'input_file' => 'file',
                    'resize' => false,
                    'save_type' => false,
                ]
            ],
            'file_storage' => 'cloudinary'
        ]);

        $this->belongsTo('OrganizationTypes', [
            'foreignKey' => 'organization_type_id'
        ]);
        $this->belongsTo('Countries', [
            'foreignKey' => 'country_id'
        ]);
        $this->belongsTo('Cities', [
            'foreignKey' => 'city_id'
        ]);
        $this->belongsTo('InstitutionTypes', [
            'foreignKey' => 'institution_type_id'
        ]);
        $this->belongsTo('CategoryOfOrganizations', [
            'foreignKey' => 'category_id'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('AdminSupports', [
            'foreignKey' => 'organization_id'
        ]);
        $this->hasMany('ConversationMessages', [
            'foreignKey' => 'organization_id'
        ]);
        $this->hasMany('ConversationParticipants', [
            'foreignKey' => 'organization_id'
        ]);
        $this->hasMany('Events', [
            'foreignKey' => 'organization_id'
        ]);
        $this->hasMany('ForumThreads', [
            'foreignKey' => 'organization_id'
        ]);
        $this->hasMany('News', [
            'foreignKey' => 'organization_id'
        ]);
        $this->hasMany('OrganizationAlumni', [
            'foreignKey' => 'organization_id'
        ]);
        $this->hasMany('OrganizationOffices', [
            'foreignKey' => 'organization_id'
        ]);
        $this->hasMany('OrganizationUsers', [
            'foreignKey' => 'organization_id'
        ]);
        $this->hasMany('Resources', [
            'foreignKey' => 'organization_id'
        ]);
        $this->hasMany('VolunteeringHistories', [
            'foreignKey' => 'organization_id'
        ]);
        $this->belongsToMany('VolunteeringCategories', [
            'foreignKey' => 'organization_id',
            'targetForeignKey' => 'volunteering_category_id',
            'joinTable' => 'organization_categories'
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
            ->scalar('about')
            ->requirePresence('about', 'create')
            ->notEmptyString('about');

        $validator
            ->scalar('logo')
            ->maxLength('logo', 255)
            ->allowEmptyString('logo');

        $validator
            ->scalar('government_affliliation')
            ->maxLength('government_affliliation', 100)
            ->allowEmptyString('government_affliliation');

        $validator
            ->date('date_of_establishment')
            ->allowEmptyDate('date_of_establishment');

        $validator
            ->scalar('phone_number')
            ->maxLength('phone_number', 16)
            ->notEmptyString('phone_number');

        $validator
            ->scalar('address')
            ->notEmptyString('address');

        $validator
            ->scalar('email')
            ->notEmptyString('email');

        $validator
            ->scalar('website')
            ->maxLength('website', 55)
            ->allowEmptyString('website');

        $validator
            ->scalar('facebook_url')
            ->maxLength('facebook_url', 255)
            ->allowEmptyString('facebook_url');

        $validator
            ->scalar('instagram_url')
            ->maxLength('instagram_url', 255)
            ->allowEmptyString('instagram_url');

        $validator
            ->scalar('twitter_url')
            ->maxLength('twitter_url', 255)
            ->allowEmptyString('twitter_url');

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        $validator
            ->scalar('pan_africanism')
            ->inList('pan_africanism', ['Yes', 'No'])
            ->allowEmptyString('pan_africanism');

        $validator
            ->scalar('education_skills')
            ->inList('education_skills', ['Yes', 'No'])
            ->allowEmptyString('education_skills');

        $validator
            ->scalar('health_wellbeing')
            ->inList('health_wellbeing', ['Yes', 'No'])
            ->allowEmptyString('health_wellbeing');

        $validator
            ->scalar('no_poverty')
            ->inList('no_poverty', ['Yes', 'No'])
            ->allowEmptyString('no_poverty');

        $validator
            ->scalar('agriculture_rural')
            ->inList('agriculture_rural', ['Yes', 'No'])
            ->allowEmptyString('agriculture_rural');

        $validator
            ->scalar('democratic_values')
            ->inList('democratic_values', ['Yes', 'No'])
            ->allowEmptyString('democratic_values');

        $validator
            ->scalar('environmental_sustainability')
            ->inList('environmental_sustainability', ['Yes', 'No'])
            ->allowEmptyString('environmental_sustainability');

        $validator
            ->scalar('infrastructure_development')
            ->inList('infrastructure_development', ['Yes', 'No'])
            ->allowEmptyString('infrastructure_development');

        $validator
            ->scalar('peace_security')
            ->inList('peace_security', ['Yes', 'No'])
            ->allowEmptyString('peace_security');

        $validator
            ->scalar('culture')
            ->inList('culture', ['Yes', 'No'])
            ->allowEmptyString('culture');

        $validator
            ->scalar('gender_inequality')
            ->inList('gender_inequality', ['Yes', 'No'])
            ->allowEmptyString('gender_inequality');

        $validator
            ->scalar('youth_empowerment')
            ->inList('youth_empowerment', ['Yes', 'No'])
            ->allowEmptyString('youth_empowerment');

        $validator
            ->scalar('reduced_inequality')
            ->inList('reduced_inequality', ['Yes', 'No'])
            ->allowEmptyString('reduced_inequality');

        $validator
            ->scalar('sustainable_city')
            ->inList('sustainable_city', ['Yes', 'No'])
            ->allowEmptyString('sustainable_city');

        $validator
            ->scalar('responsible_consumption')
            ->inList('responsible_consumption', ['Yes', 'No'])
            ->allowEmptyString('responsible_consumption');

        $validator
            ->scalar('pan_africanism_pan')
            ->maxLength('pan_africanism_pan', 255)
            ->allowEmptyString('pan_africanism_pan');

        $validator
            ->scalar('education_skills_pan')
            ->maxLength('education_skills_pan', 255)
            ->allowEmptyString('education_skills_pan');

        $validator
            ->scalar('citizen_health_pan')
            ->maxLength('citizen_health_pan', 255)
            ->allowEmptyString('citizen_health_pan');

        $validator
            ->scalar('poverty_pan')
            ->maxLength('poverty_pan', 255)
            ->allowEmptyString('poverty_pan');

        $validator
            ->scalar('pan_africanism_edu')
            ->maxLength('pan_africanism_edu', 255)
            ->allowEmptyString('pan_africanism_edu');

        $validator
            ->scalar('education_skills_edu')
            ->maxLength('education_skills_edu', 255)
            ->allowEmptyString('education_skills_edu');

        $validator
            ->scalar('citizen_health_edu')
            ->maxLength('citizen_health_edu', 255)
            ->allowEmptyString('citizen_health_edu');

        $validator
            ->scalar('poverty_edu')
            ->maxLength('poverty_edu', 255)
            ->allowEmptyString('poverty_edu');

        $validator
            ->scalar('pan_africanism_health')
            ->maxLength('pan_africanism_health', 255)
            ->allowEmptyString('pan_africanism_health');

        $validator
            ->scalar('education_skills_health')
            ->maxLength('education_skills_health', 255)
            ->allowEmptyString('education_skills_health');

        $validator
            ->scalar('citizen_health_health')
            ->maxLength('citizen_health_health', 255)
            ->allowEmptyString('citizen_health_health');

        $validator
            ->scalar('poverty_health')
            ->maxLength('poverty_health', 255)
            ->allowEmptyString('poverty_health');

        $validator
            ->scalar('pan_africanism_nopov')
            ->maxLength('pan_africanism_nopov', 255)
            ->allowEmptyString('pan_africanism_nopov');

        $validator
            ->scalar('education_skills_nopov')
            ->maxLength('education_skills_nopov', 255)
            ->allowEmptyString('education_skills_nopov');

        $validator
            ->scalar('citizen_health_nopov')
            ->maxLength('citizen_health_nopov', 255)
            ->allowEmptyString('citizen_health_nopov');

        $validator
            ->scalar('poverty_nopov')
            ->maxLength('poverty_nopov', 255)
            ->allowEmptyString('poverty_nopov');

        $validator
            ->scalar('pan_africanism_agric')
            ->maxLength('pan_africanism_agric', 255)
            ->allowEmptyString('pan_africanism_agric');

        $validator
            ->scalar('education_skills_agric')
            ->maxLength('education_skills_agric', 255)
            ->allowEmptyString('education_skills_agric');

        $validator
            ->scalar('citizen_health_agric')
            ->maxLength('citizen_health_agric', 255)
            ->allowEmptyString('citizen_health_agric');

        $validator
            ->scalar('poverty_agric')
            ->maxLength('poverty_agric', 255)
            ->allowEmptyString('poverty_agric');

        $validator
            ->scalar('pan_africanism_demo')
            ->maxLength('pan_africanism_demo', 255)
            ->allowEmptyString('pan_africanism_demo');

        $validator
            ->scalar('education_skills_demo')
            ->maxLength('education_skills_demo', 255)
            ->allowEmptyString('education_skills_demo');

        $validator
            ->scalar('citizen_health_demo')
            ->maxLength('citizen_health_demo', 255)
            ->allowEmptyString('citizen_health_demo');

        $validator
            ->scalar('poverty_demo')
            ->maxLength('poverty_demo', 255)
            ->allowEmptyString('poverty_demo');

        $validator
            ->scalar('pan_africanism_enviro')
            ->maxLength('pan_africanism_enviro', 255)
            ->allowEmptyString('pan_africanism_enviro');

        $validator
            ->scalar('education_skills_enviro')
            ->maxLength('education_skills_enviro', 255)
            ->allowEmptyString('education_skills_enviro');

        $validator
            ->scalar('citizen_health_enviro')
            ->maxLength('citizen_health_enviro', 255)
            ->allowEmptyString('citizen_health_enviro');

        $validator
            ->scalar('poverty_enviro')
            ->maxLength('poverty_enviro', 255)
            ->allowEmptyString('poverty_enviro');

        $validator
            ->scalar('pan_africanism_infra')
            ->maxLength('pan_africanism_infra', 255)
            ->allowEmptyString('pan_africanism_infra');

        $validator
            ->scalar('education_skills_infra')
            ->maxLength('education_skills_infra', 255)
            ->allowEmptyString('education_skills_infra');

        $validator
            ->scalar('citizen_health_infra')
            ->maxLength('citizen_health_infra', 255)
            ->allowEmptyString('citizen_health_infra');

        $validator
            ->scalar('poverty_infra')
            ->maxLength('poverty_infra', 255)
            ->allowEmptyString('poverty_infra');

        $validator
            ->scalar('pan_africanism_peace')
            ->maxLength('pan_africanism_peace', 255)
            ->allowEmptyString('pan_africanism_peace');

        $validator
            ->scalar('education_skills_peace')
            ->maxLength('education_skills_peace', 255)
            ->allowEmptyString('education_skills_peace');

        $validator
            ->scalar('citizen_health_peace')
            ->maxLength('citizen_health_peace', 255)
            ->allowEmptyString('citizen_health_peace');

        $validator
            ->scalar('poverty_peace')
            ->maxLength('poverty_peace', 255)
            ->allowEmptyString('poverty_peace');

        $validator
            ->scalar('pan_africanism_culture')
            ->maxLength('pan_africanism_culture', 255)
            ->allowEmptyString('pan_africanism_culture');

        $validator
            ->scalar('education_skills_culture')
            ->maxLength('education_skills_culture', 255)
            ->allowEmptyString('education_skills_culture');

        $validator
            ->scalar('citizen_health_culture')
            ->maxLength('citizen_health_culture', 255)
            ->allowEmptyString('citizen_health_culture');

        $validator
            ->scalar('poverty_culture')
            ->maxLength('poverty_culture', 255)
            ->allowEmptyString('poverty_culture');

        $validator
            ->scalar('pan_africanism_gender')
            ->maxLength('pan_africanism_gender', 255)
            ->allowEmptyString('pan_africanism_gender');

        $validator
            ->scalar('education_skills_gender')
            ->maxLength('education_skills_gender', 255)
            ->allowEmptyString('education_skills_gender');

        $validator
            ->scalar('citizen_health_gender')
            ->maxLength('citizen_health_gender', 255)
            ->allowEmptyString('citizen_health_gender');

        $validator
            ->scalar('poverty_gender')
            ->maxLength('poverty_gender', 255)
            ->allowEmptyString('poverty_gender');

        $validator
            ->scalar('pan_africanism_youth')
            ->maxLength('pan_africanism_youth', 255)
            ->allowEmptyString('pan_africanism_youth');

        $validator
            ->scalar('education_skills_youth')
            ->maxLength('education_skills_youth', 255)
            ->allowEmptyString('education_skills_youth');

        $validator
            ->scalar('citizen_health_youth')
            ->maxLength('citizen_health_youth', 255)
            ->allowEmptyString('citizen_health_youth');

        $validator
            ->scalar('poverty_youth')
            ->maxLength('poverty_youth', 255)
            ->allowEmptyString('poverty_youth');

        $validator
            ->scalar('pan_africanism_reduced')
            ->maxLength('pan_africanism_reduced', 255)
            ->allowEmptyString('pan_africanism_reduced');

        $validator
            ->scalar('education_skills_reduced')
            ->maxLength('education_skills_reduced', 255)
            ->allowEmptyString('education_skills_reduced');

        $validator
            ->scalar('citizen_health_reduced')
            ->maxLength('citizen_health_reduced', 255)
            ->allowEmptyString('citizen_health_reduced');

        $validator
            ->scalar('poverty_reduced')
            ->maxLength('poverty_reduced', 255)
            ->allowEmptyString('poverty_reduced');

        $validator
            ->scalar('pan_africanism_sustainable')
            ->maxLength('pan_africanism_sustainable', 255)
            ->allowEmptyString('pan_africanism_sustainable');

        $validator
            ->scalar('education_skills_sustainable')
            ->maxLength('education_skills_sustainable', 255)
            ->allowEmptyString('education_skills_sustainable');

        $validator
            ->scalar('citizen_health_sustainable')
            ->maxLength('citizen_health_sustainable', 255)
            ->allowEmptyString('citizen_health_sustainable');

        $validator
            ->scalar('poverty_sustainable')
            ->maxLength('poverty_sustainable', 255)
            ->allowEmptyString('poverty_sustainable');

        $validator
            ->scalar('pan_africanism_responsible')
            ->maxLength('pan_africanism_responsible', 255)
            ->allowEmptyString('pan_africanism_responsible');

        $validator
            ->scalar('education_skills_responsible')
            ->maxLength('education_skills_responsible', 255)
            ->allowEmptyString('education_skills_responsible');

        $validator
            ->scalar('citizen_health_responsible')
            ->maxLength('citizen_health_responsible', 255)
            ->allowEmptyString('citizen_health_responsible');

        $validator
            ->scalar('poverty_responsible')
            ->maxLength('poverty_responsible', 255)
            ->allowEmptyString('poverty_responsible');

        $validator
            ->scalar('volunteer_exchange_region')
            ->inList('volunteer_exchange_region', ['Yes', 'No'])
            ->allowEmptyString('volunteer_exchange_region');

        $validator
            ->scalar('volunteer_exchange_intern')
            ->inList('volunteer_exchange_intern', ['Yes', 'No'])
            ->allowEmptyString('volunteer_exchange_intern');

        $validator
            ->scalar('pan_africanism_resources')
            ->inList('pan_africanism_resources', ['Yes', 'No', 'In Progress', 'Don\'t Know'])
            ->allowEmptyString('pan_africanism_resources');

        $validator
            ->scalar('pan_africanism_organiz_pol')
            ->inList('pan_africanism_organiz_pol', ['Yes', 'No', 'In Progress', 'Don\'t Know'])
            ->allowEmptyString('pan_africanism_organiz_pol');

        $validator
            ->scalar('pan_africanism_organiz_annu')
            ->inList('pan_africanism_organiz_annu', ['Yes', 'No'])
            ->allowEmptyString('pan_africanism_organiz_annu');

        $validator
            ->scalar('pan_africanism_country_file')
            ->maxLength('pan_africanism_country_file', 255)
            ->allowEmptyString('pan_africanism_country_file');

        $validator
            ->scalar('pan_africanism_organiz_pol_file')
            ->maxLength('pan_africanism_organiz_pol_file', 255)
            ->allowEmptyString('pan_africanism_organiz_pol_file');

        $validator
            ->scalar('pan_africanism_organiz_annu_file')
            ->maxLength('pan_africanism_organiz_annu_file', 255)
            ->allowEmptyString('pan_africanism_organiz_annu_file');

        $validator
            ->scalar('additional_file')
            ->maxLength('additional_file', 255)
            ->allowEmptyString('additional_file');

        $validator
            ->allowEmptyFile('file')
            ->add('file', 'upload', [
                'rule' => ['uploadedFile', [
                    'types' => [
                        'image/jpeg', 'image/png', 'image/jpg', 'application/pdf'
                    ],
                ]],
                'on' => function ($context) {
                    return !empty($context['data']['file']);
                },
                'message' => __('The provided file type is invalid. Support file types are: {0}', ['jpeg, png, jpg and pdf'])
            ]);

        // $validator
        //     ->allowEmptyFile('profile_image')
        //     ->add('profile_image', [
        //         'validExtension' => [
        //             'rule' => ['extension', ['png', 'jpeg', 'png', 'jpg']],
        //             'message' => __('These files extension are allowed: .png')
        //         ]
        //     ]);

        $validator
            ->add('file', 'file', [
                'rule' => ['fileSize', '<=', '5MB'],
                'on' => function ($context) {
                    return !empty($context['data']['file']) && $context['data']['file']['error'] !== UPLOAD_ERR_OK;
                },
                'message' => __('Image must be less than {0}', ['5MB'])
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
        $rules->add($rules->existsIn(['organization_type_id'], 'OrganizationTypes'));
        $rules->add($rules->existsIn(['country_id'], 'Countries'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));
        $rules->add($rules->existsIn(['institution_type_id'], 'InstitutionTypes'));
        $rules->add($rules->existsIn(['category_id'], 'CategoryOfOrganizations'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }

    public function afterSave($event, $entity, $options)
    {
        if ($entity->isNew()) {
            $organizationUsersTable = TableRegistry::getTableLocator()->get('OrganizationUsers');
            $organizationUser = $organizationUsersTable->newEntity([
                'organization_id' => $entity->id,
                'user_id' => $entity->user_id,
                'role' => 'admin',
                'status' => STATUS_ACTIVE
            ]);

            $organizationsTable = TableRegistry::getTableLocator()->get('Organizations');
            $organization = $organizationsTable->find()->where(['id' => $entity->id])->first()->set([
                'status' => STATUS_INACTIVE
            ]);

            $organizationUsersTable->save($organizationUser);
            $organizationsTable->save($organization);

            // Send emails
            $usersTable = TableRegistry::getTableLocator()->get('Users');
            $user = $usersTable->get($entity->user_id);
            $adminsTable = TableRegistry::getTableLocator()->get('Admins');
            $admins = $adminsTable->find()->where(['status' => STATUS_ACTIVE])->extract('email')->toArray();

            $this->Email = new EmailSender();
            $this->Email->sendNewOrganizationUserEmail($user, 'Organization Registration Notice');
            $this->Email->sendNewOrganizationAUEmail($admins, $entity, 'Organization Registration Notice');
        }
    }
}
