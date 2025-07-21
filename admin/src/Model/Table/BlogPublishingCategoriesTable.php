<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * BlogPublishingCategories Model
 *
 * @property \App\Model\Table\BlogPostsTable&\Cake\ORM\Association\BelongsTo $BlogPosts
 * @property \App\Model\Table\PublishingCategoriesTable&\Cake\ORM\Association\BelongsTo $PublishingCategories
 *
 * @method \App\Model\Entity\BlogPublishingCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\BlogPublishingCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\BlogPublishingCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BlogPublishingCategory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogPublishingCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\BlogPublishingCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPublishingCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPublishingCategory findOrCreate($search, callable $callback = null, $options = [])
 */
class BlogPublishingCategoriesTable extends Table
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

        $this->setTable('blog_publishing_categories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('BlogPosts', [
            'foreignKey' => 'blog_post_id'
        ]);
        $this->belongsTo('PublishingCategories', [
            'foreignKey' => 'publishing_category_id'
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
        $rules->add($rules->existsIn(['publishing_category_id'], 'PublishingCategories'));

        return $rules;
    }
}
