<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    //
    protected $fillable = [
        'id',
        'name',
        'description',
        'slug',
        'status',
        'technology_basic',
        'technology_inter',
        'technology_advan'
    ];  

    protected $hidden = [
        'created_at' , 'updated_at'
    ];

    public function experts()
    {
        return $this->belongToMany('App\Expert')->withTimestamps();
    }

    public function fk_log() {
        return $this->hasMany('App\Product', 'product_type_id', 'product_type_id');
    }

    public function requirements(){
        return $this->hasMany('App\Requirement', 'position_id');
    } 
}
