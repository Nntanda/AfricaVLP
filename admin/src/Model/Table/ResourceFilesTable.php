<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\ORM\Behavior\Behavior;

class ResourceFilesTable extends Table
{
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('resource_files');
        $this->setDisplayField('file_link');
        $this->setPrimaryKey('id');

        $this->belongsTo('Resources', [
            'foreignKey' => 'resource_id',
            'joinType' => 'INNER',
        ]);

        $this->addBehavior('UploadImage', [
            'fields' => [
                'file_link' => [
                    'input_file' => 'file',
                    'resize' => false,
                    'save_type' => true,
                    'type_field' => 'file_type',
                ]
            ],
            'file_storage' => 'local'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('resource_id')
            ->requirePresence('resource_id', 'create')
            ->notEmptyString('resource_id');

        $validator
            ->scalar('file_link')
            ->maxLength('file_link', 255)
            ->requirePresence('file_link', 'create')
            ->notEmptyString('file_link');

        $validator
            ->scalar('file_type')
            ->maxLength('file_type', 50)
            ->allowEmptyString('file_type');

        return $validator;
    }
}
