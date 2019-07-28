<?php

namespace App\Models\Object;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'object_statuses';
    protected $guarded = [
        'id',
    ];
    public $timestamps = false;
}