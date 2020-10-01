<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
    public $incrementing = true;

    public $primaryKey = 'id';

    protected $table = 'sales';
    //
    protected $fillable = [
        'id',
        'job_id',
        'url',
        'freelancers_qty',
        'payment_range',
        'title',
        'status',
    ];
}
