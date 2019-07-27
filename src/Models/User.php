<?php

namespace App\Models;

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
}