<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Interview extends Model
{
    //
    public $incrementing = true;

    protected $keyType = 'integer';

    protected $fillable = [
        'type', 'description', 'about', 'result', 'date' , 'expert_id' , 'user_id'
    ];

    protected $hidden = [
        'user_id', 'created_at',
    ];

    public function expert(){
        return $this->hasOne('App\Expert', 'id', 'expert_id');
    }
    
}
