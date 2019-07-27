<?php

namespace App\Models\Object;

use Illuminate\Database\Eloquent\Model;

class Object extends Model
{
    protected $table = 'objects';
    protected $guarded = [
        'id',
    ];
}