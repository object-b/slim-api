<?php

use Phinx\Migration\AbstractMigration;

class CreateObjectEvents extends AbstractMigration
{
    public function up()
    {
        $object_events = $this->table('object_events');
        $object_events
            ->addColumn('object_id', 'integer')
            ->addColumn('user_id', 'integer')
            ->addColumn('object_status_id', 'integer', ['default' => '1'])
            ->addColumn('object_description_id', 'integer', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addForeignKey('user_id', 'users', 'id')
            ->addForeignKey('object_status_id', 'object_statuses', 'id')
            ->addForeignKey('object_id', 'objects', 'id', ['delete'=> 'CASCADE'])
            ->addForeignKey('object_description_id', 'object_descriptions', 'id', ['delete'=> 'CASCADE'])
            ->save();
    }
    
    public function down()
    {
        $this->table('object_events')->drop()->save();
    }
}