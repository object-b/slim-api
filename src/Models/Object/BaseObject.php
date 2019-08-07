<?php

namespace App\Models\Object;

use Illuminate\Database\Eloquent\Model;

class BaseObject extends Model
{
    protected $table = 'objects';
    protected $guarded = [
        'id',
    ];
    protected $dates = ['closed_at'];

    public function address()
    {
        return $this->hasOne('App\Models\Address', 'object_id');
    }

    public function description()
    {
        return $this->hasOne('App\Models\Object\Description', 'object_id');
    }

    public function events()
    {
        return $this->hasMany('App\Models\Object\Event', 'object_id');
    }

    public function status()
    {
        return $this->hasOne('App\Models\Object\Status', 'id', 'object_status_id');
    }

    public function creator()
    {
        return $this->hasOne('App\Models\User\BaseUser', 'id', 'creator_id');
    }

    public function resolver()
    {
        return $this->hasOne('App\Models\User\BaseUser', 'id', 'resolver_id');
    }
}