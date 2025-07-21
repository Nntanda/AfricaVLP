<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * InstitutionTypes Model
 *
 * @property \App\Model\Table\OrganizationsTable&\Cake\ORM\Association\HasMany $Organizations
 *
 * @method \App\Model\Entity\InstitutionType get($primaryKey, $options = [])
 * @method \App\Model\Entity\InstitutionType newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\InstitutionType[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\InstitutionType|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InstitutionType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\InstitutionType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\InstitutionType[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\InstitutionType findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class InstitutionTypesTable extends Table
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

        $this->setTable('institution_types');
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

        $this->hasMany('Organizations', [
            'foreignKey' => 'institution_type_id'
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
            ->allowEmptyString('name');

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        return $validator;
    }
}
