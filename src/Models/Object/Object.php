<?php

namespace App\Models\Object;

use Illuminate\Database\Eloquent\Model;

class Object extends Model
{
    protected $table = 'objects';
    protected $guarded = [
        'id',
    ];
    protected $dates = ['closed_at'];

    public function address()
    {
        return $this->hasOne('App\Models\Address');
    }

    public function description()
    {
        return $this->hasOne('App\Models\Object\Description');
    }

    public function events()
    {
        return $this->hasMany('App\Models\Object\Event');
    }

    public function status()
    {
        return $this->hasOne('App\Models\Object\Status', 'id', 'object_status_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User\User', 'id', 'user_id');
    }
}