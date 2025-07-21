<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Widgets Model
 *
 * @method \App\Model\Entity\Widget get($primaryKey, $options = [])
 * @method \App\Model\Entity\Widget newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Widget[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Widget|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Widget saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Widget patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Widget[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Widget findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WidgetsTable extends Table
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

        $this->setTable('widgets');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Translate', ['fields' => ['title', 'content']]);
        $this->addBehavior('UploadImage', [
            'fields' => [
                'image' => [
                    'input_file' => 'file',
                    'resize' => false,
                    'save_type' =>false,
                ]
            ],
            'file_storage' => 'cloudinary'
        ]);
        $this->addBehavior('Elastic/ActivityLogger.Logger', [
            'logModel' => 'ActivityLogs',
            'scope' => [
                '\App',
            ],
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
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('title')
            ->maxLength('title', 50)
            ->requirePresence('title', 'create')
            ->notEmptyString('title')
            ->add('title', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('content')
            ->allowEmptyString('content');

        $validator
            ->scalar('image')
            ->maxLength('image', 255)
            ->requirePresence('image', 'create')
            ->allowEmptyFile('image');

        $validator
            ->integer('status')
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

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

        $validator
            ->scalar('url')
            ->maxLength('url', 255)
            ->allowEmptyString('url');

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
        $rules->add($rules->isUnique(['title']));

        return $rules;
    }
}
