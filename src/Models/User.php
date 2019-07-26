<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'password',
    ];

    public function getSomething()
    {
        return 1;
    }
}