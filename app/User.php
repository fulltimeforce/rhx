<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','name', 'email', 'password', 'role_id' , 'access_token' , 'default_page'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static $status = [
        "DISABLED" => 0,
        "ENABLED" => 1
    ];

    public function fk_log() {
        return $this->hasMany('App\User', 'user_id', 'id');
    }

    public function role(){
        return $this->hasOne('App\Role', 'id' , 'role_id');
    }
}
