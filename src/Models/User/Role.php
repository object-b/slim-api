<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'user_roles';
    protected $guarded = [
        'id',
    ];
}