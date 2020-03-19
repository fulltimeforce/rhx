<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    //
    public $incrementing = true;

    protected $fillable = [
        'id',
        'date',
        'time',
        'position_id',
        'user_id',
        'expert_id',
        'notes'
    ];

    public function position(){
        return $this->belongsTo('App\Position', 'position_id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
