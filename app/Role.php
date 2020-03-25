<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    public $incrementing = true;
    
    protected $fillable = [
        'id',
        'name'
    ];
}
