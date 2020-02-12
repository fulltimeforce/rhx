<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    public function experts()
    {
        return $this->belongToMany('App\Expert')->withTimestamps();
    }
}
