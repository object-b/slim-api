<?php

use Phinx\Migration\AbstractMigration;

class CreateLinkedSocialAccountsTable extends AbstractMigration
{
    public function up()
    {
        $linked_social_accounts = $this->table('linked_social_accounts');
        $linked_social_accounts    
            ->addColumn('provider_id', 'string')
            ->addColumn('provider_name', 'string')
            ->addColumn('user_id', 'integer')
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addForeignKey('user_id', 'users', 'id')
            ->save();
    }
    
    public function down()
    {
        $this->table('linked_social_accounts')->drop()->save();
    }
}