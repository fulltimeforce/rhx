<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Portfolioexpert extends Model
{
    //
    public $incrementing = true;

    public $primaryKey = 'id';

    protected $table = 'portfolio_expert';
    //
    protected $fillable = [
        'expert_id',
        'fullname',
        'work',
        'age',
        'email',
        'address',
        'availability',
        'slug',
        'user_id',
        'github',
        'linkedin',
        'facebook',
        'photo',
        'description',
        'resume',
        'education', // serialize array
        'employment', // serialize array
        'skills', // serialize array
        'projects', // serialize array
    ];

    public function expert() {
        return $this->hasOne('App\Expert', 'id', 'expert_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
