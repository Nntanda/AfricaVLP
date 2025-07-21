<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * NewsletterContents Model
 *
 * @property \App\Model\Table\ObjectsTable&\Cake\ORM\Association\BelongsTo $Objects
 *
 * @method \App\Model\Entity\NewsletterContent get($primaryKey, $options = [])
 * @method \App\Model\Entity\NewsletterContent newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\NewsletterContent[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\NewsletterContent|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NewsletterContent saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\NewsletterContent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\NewsletterContent[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\NewsletterContent findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class NewsletterContentsTable extends Table
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

        $this->setTable('newsletter_contents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('News', [
            'foreignKey' => 'object_id',
            'conditions' => ['NewsletterContents.object_model' => 'News']
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
            ->scalar('object_model')
            ->maxLength('object_model', 20)
            ->requirePresence('object_model', 'create')
            ->notEmptyString('object_model');

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
        $rules->add(function ($entity, $options) use($rules) {
            $rule = $rules->existsIn('object_id', $entity->object_model);
            return $rule($entity, $options);
        }, 'objectExists');

        return $rules;
    }
}
