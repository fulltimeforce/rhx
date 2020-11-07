<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    //
    protected $table = 'notifications';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'search_name',
        'expert_name',
        'user_level',
        'state',
    ];
}
