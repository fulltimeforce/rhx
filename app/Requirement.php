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

    ];

    public function position(){
        return $this->belongsTo('App\Position', 'position_id');
    }
}
