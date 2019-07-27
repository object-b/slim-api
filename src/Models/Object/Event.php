<?php

namespace App\Models\Object;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'object_events';
    protected $guarded = [
        'id',
    ];
}