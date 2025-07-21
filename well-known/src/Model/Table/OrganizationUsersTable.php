<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use App\Email\EmailSender;

/**
 * OrganizationUsers Model
 *
 * @property \App\Model\Table\OrganizationsTable&\Cake\ORM\Association\BelongsTo $Organizations
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\OrganizationUser get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrganizationUser newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OrganizationUser[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationUser|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrganizationUser saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrganizationUser patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationUser[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrganizationUser findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class OrganizationUsersTable extends Table
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

        $this->setTable('organization_users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Organizations', [
            'foreignKey' => 'organization_id'
        ]);
        $this->belongsTo('Users', [
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
            ->scalar('role')
            ->maxLength('role', 45)
            ->notEmptyString('role');

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
        $rules->add($rules->existsIn(['organization_id'], 'Organizations'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->isUnique(['organization_id', 'user_id']));

        return $rules;
    }

    public function afterSave($event, $entity, $options)
    {
        if ($entity->isNew()) {
            // Send emails
            $usersTable = TableRegistry::getTableLocator()->get('Users');
            $user = $usersTable->get($entity->user_id);
            $organizationsTable = TableRegistry::getTableLocator()->get('Organizations');
            $organization = $organizationsTable->get($entity->organization_id, ['contain' => ['Users']]);
            
            $this->Email = new EmailSender();
            $this->Email->sendOrganizationUserEmail($user, $organization->name, $entity->role);
            $this->Email->sendOrganizationUserAddedEmail($user, $organization->user, $entity->role);
        }
    }
}
