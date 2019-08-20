<?php

use Phinx\Migration\AbstractMigration;

class CreateObjectAddresses extends AbstractMigration
{
    public function up()
    {
        $object_addresses = $this->table('object_addresses');
        $object_addresses
            ->addColumn('object_id', 'integer')
            ->addColumn('display_name', 'text')
            ->addColumn('city', 'string', ['null' => true])
            ->addColumn('city_district', 'string', ['null' => true])
            ->addColumn('county', 'string', ['null' => true])
            ->addColumn('state', 'string', ['null' => true])
            ->addColumn('country', 'string', ['null' => true])
            ->addColumn('latitude', 'decimal', ['precision' => '12', 'scale' => '6'])
            ->addColumn('longitude', 'decimal', ['precision' => '12', 'scale' => '6'])
            ->addIndex(['latitude', 'longitude'])
            ->addForeignKey('object_id', 'objects', 'id', ['delete'=> 'CASCADE'])
            ->save();
    }
    
    public function down()
    {
        $this->table('object_addresses')->drop()->save();
    }
}