<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Recruit;

use Mail;
use Illuminate\Support\Facades\DB;

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
        $recruit = Recruit::where('id','VM2vw66aPnjlwo0J');
        if($recruit->count() > 0){
            $recruit->update([
                'raven_date'=>date('Y-m-d H:i:s'),//'2021-01-20 20:00:00'
            ]);
        }
    }
}
