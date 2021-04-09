<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    public $incrementing = true;

    public $primaryKey = 'id';

    //
    protected $fillable = [
        'id',
        'name',
    ];
}
