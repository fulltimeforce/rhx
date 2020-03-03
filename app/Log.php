<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    //
    protected $fillable = [
        'id',
        'name',
        'positions',
        'platform',
        'link',
        'form',
        'filter',
        'called',
        'scheduled',
        'attended',
        'approve',
        'user_id'
    ];

    protected $hidden = [
        'created_at' , 'updated_at' , 'user_id'
    ];

    public function position(){
        return $this->hasOne('App\Position', 'id', 'positions');
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'user');
    }

    
}
