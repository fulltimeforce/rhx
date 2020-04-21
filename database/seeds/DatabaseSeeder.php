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

        // $resumes = DB::table('portfolio_expert')->get();

        // foreach ($resumes as $key => $resume) {
        //     if( !is_null( $resume->skills ) && $resume->skills != '' ){
        //         $skills = unserialize( $resume->skills );
        //         $_n_skills_advanced = array();
        //         $_n_skills_intermediate = array();
        //         foreach ($skills as $skey => $skill) {
        //             if( $skill['value'] != 'basic' && $skill['value'] == 'advanced' ){
        //                 $_n_skills_advanced[] = $skill;
        //             }
        //             if( $skill['value'] != 'basic' && $skill['value'] == 'intermediate' ){
        //                 $_n_skills_intermediate[] = $skill;
        //             }
        //         }

        //         $_skills = array_merge( $_n_skills_advanced , $_n_skills_intermediate );

        //         DB::table('portfolio_expert')->where('id' , $resume->id)->update(
        //             array(
        //                 "skills" => serialize($_skills)
        //             )
        //         );
        //     }
            
        // }

        Recruiterlog::with(['expert'])->where('id' , '>', 37)->delete();
        DB::table('expert_log')->where('log_id', '>' , 37 )->delete();
        Notelog::where('log_id', '>' , 37 )->delete();

        $interviews = Interview::with(['expert'])->whereIn('user_id' , [5])->get();
        print_r("--------------");
        
        $log_expert=array();

        foreach ($interviews as $key => $interview) {
            print_r($interview->id);
            $array = array(
                "date"          => date("Y-m-d"),
                "expert"        => $interview->expert->fullname,
                "user_id"       => $interview->user_id,
                "position_id"   => null,
                "platform"      => "internal database",
                "contact"       => 'filled form',
            );
            
            $type = '';
            if( $interview->type == 'commercial' ){
                $array['commercial'] = !$interview->result ? 'not approved' : 'approved';
                $type = 'commercial';
            }else if( $interview->type == 'technical' ){
                $array['technique'] = !$interview->result ? 'not approved' : 'approved';
                $type = 'technique';
            }else if( $interview->type == 'psychological' ){
                $array['psychology'] = !$interview->result ? 'not approved' : 'approved';
                $type = 'psychology';
            }

            if( $interview->type == 'psychological' || $interview->type == 'technical' || $interview->type == 'commercial' ){
                $log_id = 0;

                if( !in_array( $interview->expert->id , $log_expert ) ){
                    $log = Recruiterlog::create(
                        $array
                    );

                    DB::table('expert_log')->insert(
                        array(
                            "expert_id" => $interview->expert->id,
                            "log_id"    => $log->id
                        )
                    );

                    $log_id = $log->id;
                }else{
                    
                    $log = DB::table('expert_log')->where("expert_id" , $interview->expert->id)->first();
                    $log_id = $log->log_id;
                    Recruiterlog::where('id' , $log_id)->update($array);
                }
                
                Notelog::create(
                    array(
                        "log_id"    => $log_id,
                        "type"      => $type,
                        "note"      => $interview->about ."\n" . $interview->description ,
                        "date"      => $interview->date
                    )
                );

                $log_expert[] = $interview->expert->id;
            }
            
        }
    }
}
