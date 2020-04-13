<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    public $incrementing = true;

    public $primaryKey = 'id';

    //
    protected $fillable = [
        'id',
        'expert_id',
        'link',
        'description',
    ];
}
