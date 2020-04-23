<?php

use Illuminate\Database\Seeder;
use App\Interview;
use App\Recruiterlog;
use App\Notelog;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // $resumes = DB

        $resumes = DB::table('portfolio_expert')->get();

        foreach ($resumes as $key => $resume) {
            if( !is_null( $resume->projects ) && $resume->projects != '' ){
                $projects = unserialize( $resume->projects );
                
                $n_projects = array();

                foreach ($projects as $pkey => $project) {
                    // if( !isset($project['image_name']) ) {break};
                    $_project = $project;
                    if( !is_null( $_project['image_name'] ) && $_project['image_name'] != '' ){
                        $image_name = $_project['image_name'];
                        $_project["images"] = array(
                            $image_name
                        );
                        $_project["videos"] = array();
                    }
                    $n_projects[] = $_project;
                }

                DB::table('portfolio_expert')->where('id' , $resume->id)->update(
                    array(
                        "projects" => serialize($n_projects)
                    )
                );
            }
            
        }

        // Recruiterlog::with(['expert'])->where('id' , '>', 37)->delete();
        // DB::table('expert_log')->where('log_id', '>' , 37 )->delete();
        // Notelog::where('log_id', '>' , 37 )->delete();

        
    }
}
