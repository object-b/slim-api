<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    const CITIZEN = 1;
    const ADMIN = 2;
    const GOVERNMENT = 3;

    protected $table = 'user_roles';
    protected $guarded = [
        'id',
    ];
}