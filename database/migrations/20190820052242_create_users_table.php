<?php

use Phinx\Migration\AbstractMigration;

class CreateUsersTable extends AbstractMigration
{
    public function up()
    {
        $users = $this->table('users');
        $users
            ->addColumn('user_role_id', 'integer', ['default' => '1'])
            ->addColumn('user_status_id', 'integer', ['default' => '1'])
            ->addColumn('name', 'string', ['null' => true])
            ->addColumn('api_key', 'text', ['null' => true])
            ->addColumn('email', 'string', ['null' => true])
            ->addColumn('points', 'integer', ['default' => '0'])
            ->addColumn('email_verified_at', 'timestamp', ['null' => true])
            ->addColumn('password', 'string', ['null' => true])
            ->addColumn('remember_token', 'string', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['null' => true])
            ->addColumn('updated_at', 'timestamp', ['null' => true])
            ->addIndex(['email'], ['unique' => true])
            ->addForeignKey('user_role_id', 'user_roles', 'id')
            ->addForeignKey('user_status_id', 'user_statuses', 'id')
            ->save();
    }
    
    public function down()
    {
        $this->table('users')->drop()->save();
    }
}