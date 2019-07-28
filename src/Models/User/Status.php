<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'user_statuses';
    protected $guarded = [
        'id',
    ];
}