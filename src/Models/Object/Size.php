<?php

namespace App\Models\Object;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    const SIZES_UNIQID = ['bag', 'cart', 'car'];

    protected $table = 'object_sizes';
    protected $guarded = [
        'id',
    ];
    public $timestamps = false;

    public static function findByRef($uniqid)
    {
        return self::where('ref', $uniqid)->first();
    }
}