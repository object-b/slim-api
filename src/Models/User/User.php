<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'password',
        'email_verified_at',
        'remember_token'
    ];
    
    public static function getByApiKey($key)
    {
        return self::where('api_key', $key)->first();
    }

    public function status()
    {
        return $this->hasOne('App\Models\User\Status', 'id', 'user_status_id');
    }

    public function role()
    {
        return $this->hasOne('App\Models\User\Role', 'id', 'user_role_id');
    }
}