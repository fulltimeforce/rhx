<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'note'
    ];

    public function log(){
        return $this->hasOne('App\Recruiterlog', 'id', 'log_id');
    }
}
