<?php

namespace App\Models\Object;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    protected $table = 'object_descriptions';
    protected $guarded = [
        'id',
    ];
}