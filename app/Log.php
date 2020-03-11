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
        'phone',
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
        'updated_at' , 'user_id'
    ];

    public function position(){
        return $this->hasOne('App\Position', 'id', 'positions');
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'user');
    }

    protected static $platforms = array(
        array(
            "id" => "linkedin",
            "label" => "Linkedin"
        ),
        array(
            "id" => "computrabajo",
            "label" => "Computrabajo"
        ),
        array(
            "id" => "indeed",
            "label" => "Indeed"
        ),
        array(
            "id" => "getonboard",
            "label" => "GetOnBoard"
        ),
        array(
            "id" => "bumeran",
            "label" => "Bumeran"
        ),
        array(
            "id" => "catolica",
            "label" => "PUCP"
        ),
        array(
            "id" => "upc",
            "label" => "UPC"
        ),
        array(
            "id" => "ulima",
            "label" => "UL"
        ),
        array(
            "id" => "ricardopalma",
            "label" => "URP"
        ),
        array(
            "id" => "utp",
            "label" => "UTP"
        ),
        array(
            "id" => "fb",
            "label" => "Facebook"
        )
    );
    public static function getPlataforms(){
        return self::$platforms;
    }

    
}
