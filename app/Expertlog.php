<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expertlog extends Model
{
    //
    public $incrementing = true;

    public $primaryKey = 'id';

    protected $table = 'expert_log';

    protected $fillable = [
        'id',
        'expert_id',
        'log_id',
    ];

    public function expert(){
        return $this->hasOne('App\Expert', 'id', 'expert_id');
    }

    public function log(){
        return $this->hasOne('App\Recruiterlog', 'id', 'log_id');
    }
}
