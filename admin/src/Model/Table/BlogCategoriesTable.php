<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BlogCategories Model
 *
 * @property \App\Model\Table\BlogPostsTable&\Cake\ORM\Association\BelongsTo $BlogPosts
 * @property \App\Model\Table\VolunteeringCategoriesTable&\Cake\ORM\Association\BelongsTo $VolunteeringCategories
 *
 * @method \App\Model\Entity\BlogCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\BlogCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BlogCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BlogCategory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BlogCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BlogCategory findOrCreate($search, callable $callback = null, $options = [])
 */
class BlogCategoriesTable extends Table
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

        $this->setTable('blog_categories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('BlogPosts', [
            'foreignKey' => 'blog_post_id'
        ]);
        $this->belongsTo('VolunteeringCategories', [
            'foreignKey' => 'volunteering_category_id'
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
        $rules->add($rules->existsIn(['blog_post_id'], 'BlogPosts'));
        $rules->add($rules->existsIn(['volunteering_category_id'], 'VolunteeringCategories'));

        return $rules;
    }
}
