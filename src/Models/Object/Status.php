<?php

namespace App\Models\Object;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    const PUBLISHED = 1;
    const CONFIRMED = 2;
    const CLEARED = 3;
    const BANNED = 4;

    protected $table = 'object_statuses';
    protected $guarded = [
        'id',
    ];
    public $timestamps = false;
}