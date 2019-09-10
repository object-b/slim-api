<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    const ACTIVE = 1;
    const INACTIVE = 2;
    const BANNED = 3;

    protected $table = 'user_statuses';
    protected $guarded = [
        'id',
    ];
}