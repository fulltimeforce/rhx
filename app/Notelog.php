<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notelog extends Model
{
    //
    public $incrementing = true;

    public $primaryKey = 'id';

    protected $table = 'note_log';
    //
    protected $fillable = [
        'log_id',
        'type',
        'note',
        'date'
    ];

    public function log(){
        return $this->hasOne('App\Recruiterlog', 'id', 'log_id');
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format(config('app.date_format'));
    }
}
