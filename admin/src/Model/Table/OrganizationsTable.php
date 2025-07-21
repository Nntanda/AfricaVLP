<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
    const VOLUNTEERING_ORG = 1;
    const GOVERNMENT_ORG = 2;
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
        $this->hasMany('OrganizationCategories', [
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
            ->allowEmptyString('name');

        $validator
            ->scalar('about')
            ->allowEmptyString('about');

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
            ->scalar('address')
            ->maxLength('address', 255)
            ->requirePresence('address', 'create')
            ->notEmptyString('address');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('phone_number')
            ->maxLength('phone_number', 16)
            ->notEmptyString('phone_number');

        $validator
            ->scalar('website')
            ->maxLength('website', 55)
            ->allowEmptyString('website');

        $validator
            ->scalar('facebbok_url')
            ->maxLength('facebbok_url', 255)
            ->allowEmptyString('facebbok_url');

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
        $rules->add($rules->existsIn(['organization_type_id'], 'OrganizationTypes'));
        $rules->add($rules->existsIn(['country_id'], 'Countries'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));
        $rules->add($rules->existsIn(['institution_type_id'], 'InstitutionTypes'));
        $rules->add($rules->existsIn(['category_id'], 'CategoryOfOrganizations'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}
