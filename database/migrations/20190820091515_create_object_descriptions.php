<?php

use Phinx\Migration\AbstractMigration;

class CreateObjectDescriptions extends AbstractMigration
{
    public function up()
    {
        $object_descriptions = $this->table('object_descriptions');
        $object_descriptions
            ->addColumn('object_id', 'integer')
            ->addColumn('title', 'string', ['null' => true])
            ->addColumn('description', 'text')
            ->addForeignKey('object_id', 'objects', 'id', ['delete'=> 'CASCADE'])
            ->save();
    }
    
    public function down()
    {
        $this->table('object_descriptions')->drop()->save();
    }
}