<?php

namespace App\Model\Table;

use Cake\Core\Configure;
use Cake\Database\Schema\TableSchema;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
// use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Elastic\ActivityLogger\Model\Table\ActivityLogsTable as Table;

/**
 * ActivityLogs Model
 */
class ActivityLogsTable extends Table
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

        $this->setTable('admin_activity_logs');

        $this->belongsTo('Admins', [
            'foreignKey' => 'issuer_id'
        ]);
    }

}
