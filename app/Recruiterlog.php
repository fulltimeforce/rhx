<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Recruiterlog extends Model
{
    //
    public $incrementing = true;

    public $primaryKey = 'id';

    protected $table = 'recruiter_logs';
    //
    protected $fillable = [
        'date',
        'expert',
        'position_id',
        'user_id',
        'platform',
        'link',
        'info',
        'contact',
        'cv',
        'experience',
        'communication',
        'english',
        'schedule',
        'commercial',
        'technique',
        'psychology',
        'filter_audio',
        'evaluate_audio'
        
    ];


    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format(config('app.date_format'));
    }

    public function position(){
        return $this->hasOne('App\Position', 'id', 'position_id')->where('status', '=', 'enabled');
    }

    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function experts(){
        return $this->belongsToMany('App\Expert' , 'expert_log' , 'log_id' , 'expert_id');
    }
    
}
