<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $incrementing = true;

    public $primaryKey = 'id';

    protected $table = 'expert_position';
    //
    protected $fillable = [
        'expert_id',
        'position_id',
        'platform',
        'link',
        'form',
        'filter',
        'called',
        'scheduled',
        'attended',
        'approve',
        'user_id',
        'status'
    ];

    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    public function position(){
        return $this->hasOne('App\Position', 'id', 'position_id')->where('status', '=', 'enabled');
    }

    public function expert(){
        return $this->hasOne('App\Expert', 'id', 'expert_id');
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
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
