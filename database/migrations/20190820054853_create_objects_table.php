<?php

use Phinx\Migration\AbstractMigration;

class CreateObjectsTable extends AbstractMigration
{
    public function up()
    {
        $objects = $this->table('objects');
        $objects    
            ->addColumn('creator_id', 'integer')
            ->addColumn('resolver_id', 'integer', ['null' => true])
            ->addColumn('object_status_id', 'integer', ['default' => '1'])
            ->addColumn('object_size_id', 'integer', ['default' => '1'])
            ->addColumn('points', 'integer', ['default' => '0'])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addColumn('closed_at', 'timestamp', ['null' => true])
            ->addForeignKey('creator_id', 'users', 'id')
            ->addForeignKey('resolver_id', 'users', 'id')
            ->addForeignKey('object_status_id', 'object_statuses', 'id')
            ->addForeignKey('object_size_id', 'object_sizes', 'id')
            ->save();
    }
    
    public function down()
    {
        $this->table('objects')->drop()->save();
    }
}