<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
	protected $table = "config";
	protected $fillable = ['fce_lower_overall'];
	public $timestamps = false;

	protected static $fce_values_total = array(
        "A1-"   => array(0,2),
        "A1"    => array(2,4),
        "A1+"   => array(4,6),
        "A2-"   => array(6,(22/3)),
        "A2"    => array((22/3),(26/3)),
        "A2+"   => array((26/3),10),
        "B1-"   => array(10,(34/3)),
        "B1"    => array((34/3),(38/3)),
        "B1+"   => array((38/3),14),
        "B2-"   => array(14,(44/3)),
        "B2"    => array((44/3),(46/3)),
        "B2+"   => array((46/3),16),
        "C1-"   => array(16,(50/3)),
        "C1"    => array((50/3),(52/3)),
        "C1+"   => array((52/3),18),
        "C2-"   => array(18,(56/3)),
        "C2"    => array((56/3),(58/3)),
        "C2+"   => array((58/3),21),
    );

	public static function getAllFceValue(){
        return self::$fce_values_total;
    }

    public static function getListFceSuperior($minFce){
        // THIS METHOD WORKS KNOWING THE $fce_values_total is constructed in order, incrementing the value.
        $fceList = [];
        $foundMin = false;
        foreach(self::$fce_values_total as $k => $v){
            if($k == $minFce){
                $foundMin = true;
            }
            if($foundMin){
                $fceList[] = $k;
            }
        }
        return $fceList;
    }
}
