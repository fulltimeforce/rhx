<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recruit extends Model
{
    protected $table = 'recruit';

    public $incrementing = false;
    protected $keyType = 'string';
    
    //
    protected $fillable = [
        'id',
        'fullname',
        'identification_number',
        'platform',
        'phone_number',
        'email_address',
        'profile_link',
        'file_path',
    ];

    protected $casts = [
        'id' => 'string'
    ];
}
