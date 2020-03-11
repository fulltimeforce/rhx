<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    //
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'position_id',
        'user_id'
    ];

    protected $hidden = ['user_id'];

    protected static $defaults = array(
        'WORKING STATUS',
        'AVAILABILITY',
        'ENGLISH LEVEL',
        'SALARY EXPERCTATION',
        'NOTES',
        'INTERVIEW'
    );

    public function position(){
        return $this->belongsTo('App\Position', 'position_id');
    }

    public static function getDefault(){
        return self::$defaults;
    }
}
