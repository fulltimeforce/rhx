<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchHistory extends Model
{
    //
    protected $table = 'search_history';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'search',
        'basic',
        'intermediate',
        'advanced',
        'audio',
        'selection',
        'name',
        'search_notify_options',
        'search_user_level',
        'search_name',
    ];
}
