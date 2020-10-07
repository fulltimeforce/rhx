<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecruitPosition extends Model
{
    public $primaryKey = 'id';
    
    protected $table = 'recruit_positions';
    //
    protected $fillable = [
        'id',
        'recruit_id',
        'position_id',
        'user_id',
        'outstanding_report',
        'call_report',
    ];
}
