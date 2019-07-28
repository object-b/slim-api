<?php

namespace App\Models\Object;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'object_addresses';
    protected $guarded = [
        'id',
    ];
    public $timestamps = false;
}