<?php

namespace App\Models\Object;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'object_events';
    protected $guarded = [
        'id',
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User\BaseUser', 'id', 'user_id');
    }

    public function object()
    {
        return $this->hasOne('App\Models\Object\BaseObject', 'id');
    }

    public function status()
    {
        return $this->hasOne('App\Models\Object\Status', 'id', 'object_status_id');
    }

    public function description()
    {
        return $this->hasOne('App\Models\Object\Description', 'id', 'object_description_id');
    }
}