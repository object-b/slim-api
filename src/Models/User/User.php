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
    
    public static function getByToken($token)
    {
        return self::where('api_token', $token)->first();
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