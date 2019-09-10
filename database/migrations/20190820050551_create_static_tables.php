<?php

use Phinx\Migration\AbstractMigration;

class CreateStaticTables extends AbstractMigration
{
    public function up()
    {
      $sizes = [
          [
            'id'    => 1,
            'name'  => 'Помещается в мешок',
            'ref' => 'bag',
          ],
          [
            'id'    => 2,
            'name'  => 'Помещается в тележку',
            'ref' => 'cart',
          ],
          [
            'id'    => 3,
            'name'  => 'Нужна машина',
            'ref' => 'car',
          ],
      ];

      $statuses = [
          [
            'id'    => 1,
            'name'  => 'Опубликовано',
            'ref' => 'publish',
          ],
          [
            'id'    => 2,
            'name'  => 'Подтверждено',
            'ref' => 'confirm',
          ],
          [
            'id'    => 3,
            'name'  => 'Убрано',
            'ref' => 'resolve',
          ],
          [
            'id'    => 4,
            'name'  => 'Заблокирован',
            'ref' => 'ban',
          ],
      ];

        $u_statuses = [
            [
              'id'    => 1,
              'name'  => 'Активный'
            ],
            [
              'id'    => 2,
              'name'  => 'Неактивный'
            ],
            [
              'id'    => 3,
              'name'  => 'Бан'
            ],
        ];

        $roles = [
            [
              'id'    => 1,
              'name'  => 'Житель'
            ],
            [
              'id'    => 2,
              'name'  => 'Админ'
            ],
            [
              'id'    => 3,
              'name'  => 'Государство'
            ],
        ];
        
        $object_sizes = $this->table('object_sizes');
        $object_sizes
            ->addColumn('name', 'string', ['limit' => 300])
            ->addColumn('ref', 'string', ['limit' => 200, 'null' => true])
            ->save();
        $this->table('object_sizes')->insert($sizes)->save();

        $object_statuses = $this->table('object_statuses');
        $object_statuses
            ->addColumn('name', 'string', ['limit' => 300])
            ->addColumn('ref', 'string', ['limit' => 200, 'null' => true])
            ->save();
        $this->table('object_statuses')->insert($statuses)->save();

        $user_roles = $this->table('user_roles');
        $user_roles
            ->addColumn('name', 'string', ['limit' => 300])
            ->addColumn('ref', 'string', ['limit' => 200, 'null' => true])
            ->save();
        $this->table('user_roles')->insert($roles)->save();

        $password_resets = $this->table('password_resets');
        $password_resets
            ->addColumn('email', 'string')
            ->addColumn('token', 'string')
            ->addColumn('created_at', 'timestamp')
            ->save();

        $user_statuses = $this->table('user_statuses');
        $user_statuses
            ->addColumn('name', 'string', ['limit' => 300])
            ->addColumn('ref', 'string', ['limit' => 200, 'null' => true])
            ->save();
        $this->table('user_statuses')->insert($u_statuses)->save();
    }
    
    public function down()
    {
        $this->table('object_sizes')->drop()->save();
        $this->table('object_statuses')->drop()->save();
        $this->table('user_roles')->drop()->save();
        $this->table('password_resets')->drop()->save();
        $this->table('user_statuses')->drop()->save();
    }
}