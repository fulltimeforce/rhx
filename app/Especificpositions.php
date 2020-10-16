<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especificpositions extends Model
{
    protected $table = 'especificpositions';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'description',
        'slug',
        'icon',
        'status',
        'technology_basic',
        'technology_inter',
        'technology_advan',
        'private',
    ];
}
