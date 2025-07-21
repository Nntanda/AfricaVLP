<?php
use Migrations\AbstractMigration;

class CreateResourceFiles extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('resource_files');
        $table->addColumn('resource_id', 'integer', ['null' => false])
              ->addColumn('file_link', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('file_type', 'string', ['limit' => 50, 'null' => true])
              ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
              ->addForeignKey('resource_id', 'resources', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
              ->create();
    }
}
