<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Recruit;
use App\RecruitPosition;
use App\Mail\ravenEmail;

use Mail;
use MultiMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class sendRaven extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'raven:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Raven Test links to everyone who scheduled links at the present hour';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {        
        $email_data = [];
        $recruits = Recruit::whereNotNull('raven_date')
                    ->select('id','fullname','email_address','raven_date');
        if($recruits->count() > 0){
            $recruits = $recruits->get();
            foreach($recruits as $recruit){

                $ravenTime = strtotime($recruit->raven_date);
                $date = date('Y-m-d',$ravenTime);
                $time = date('H', $ravenTime);

                // If there scheduled for today and this present hour
                if($date == date('Y-m-d') && $time == date('H')){
                    
                    $positions = RecruitPosition::join('users','recruit_positions.user_id','=','users.id')
                            ->where('recruit_positions.recruit_id',$recruit->id)
                            ->select('recruit_positions.id','users.email')
                            ->orderBy('recruit_positions.created_at','DESC');
                            
                    if($positions->count() > 0){
                        $position = $positions->first();
                        $email_data[] = [
                            'id'=>$recruit->id,
                            'mail'=>$recruit->email_address, 
                            'name'=>$recruit->fullname,
                            'recruit'=>$position->email,
                        ];
                    }
                }
            }
        }

        // Send mails to everyone who scheduled
        if(!empty($email_data)){
            foreach($email_data as $data){
                $query = [
                    'recruitId' => $data['id'],
                    'position' => time(),
                ];
                
                $url = URL::temporarySignedRoute(
                    'recruit.quiz', now()->addHours(2), $query
                );

                MultiMail::to('alejandro.daza@fulltimeforce.com') //$data['mail']
                    ->from($data['recruit'])
                    ->send(new ravenEmail($data['name'],$url));
            }
            return $email_data;
        }

        return 'no mails scheduled';
    }
}
