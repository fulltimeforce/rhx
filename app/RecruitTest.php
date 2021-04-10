<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecruitTest extends Model
{
    public $primaryKey = 'id';
    
    protected $table = 'recruit_test';
    //
    /*
    -- STATUS MAP
    
    mail_sent:
         0 -> not sent
         1 -> sent

    test_status:
         0 -> pending
         1 -> completed
    */

    protected $fillable = [
        'id',
        'recruit_id',
        'completeness_score',
        'code_score',
        'design_score',
        'techonolgies_score',
        'readme_score',
        'mail_sent',
        'test_status',
        'sent_at',
    ];
}
