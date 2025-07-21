<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CategoryOfResources Model
 *
 * @property \App\Model\Table\ResourceCategoriesTable&\Cake\ORM\Association\HasMany $ResourceCategories
 *
 * @method \App\Model\Entity\CategoryOfResource get($primaryKey, $options = [])
 * @method \App\Model\Entity\CategoryOfResource newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CategoryOfResource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CategoryOfResource|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CategoryOfResource saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CategoryOfResource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CategoryOfResource[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CategoryOfResource findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CategoryOfResourcesTable extends Table
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

        $this->setTable('category_of_resources');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Translate', ['fields' => ['name']]);

        $this->hasMany('ResourceCategories', [
            'foreignKey' => 'category_of_resource_id'
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
            ->allowEmptyString('name', false);

        $validator
            ->integer('status')
            ->allowEmptyString('status');

        return $validator;
    }
}
