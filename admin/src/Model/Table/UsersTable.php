<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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

        $this->belongsTo('Countries', [
            'foreignKey' => 'resident_country_id'
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
            ->allowEmptyString('first_name');

        $validator
            ->scalar('last_name')
            ->maxLength('last_name', 45)
            ->allowEmptyString('last_name');

        $validator
            ->email('email')
            ->allowEmptyString('email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->allowEmptyString('password');

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
            ->scalar('gender')
            ->maxLength('gender', 10)
            ->allowEmptyString('gender');

        $validator
            ->date('date_of_birth')
            ->allowEmptyDate('date_of_birth');

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
            ->scalar('availability')
            ->maxLength('availability', 10)
            ->allowEmptyString('availability');

        $validator
            ->boolean('is_email_verified')
            ->notEmptyString('is_email_verified');

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
        $rules->add($rules->existsIn(['resident_country_id'], 'Countries'));
        $rules->add($rules->existsIn(['city_id'], 'Cities'));

        return $rules;
    }
}
