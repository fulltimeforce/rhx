<?php

use Illuminate\Database\Seeder;

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
            if( !is_null( $resume->skills ) && $resume->skills != '' ){
                $skills = unserialize( $resume->skills );
                $_n_skills_advanced = array();
                $_n_skills_intermediate = array();
                foreach ($skills as $skey => $skill) {
                    if( $skill['value'] != 'basic' && $skill['value'] == 'advanced' ){
                        $_n_skills_advanced[] = $skill;
                    }
                    if( $skill['value'] != 'basic' && $skill['value'] == 'intermediate' ){
                        $_n_skills_intermediate[] = $skill;
                    }
                }

                $_skills = array_merge( $_n_skills_advanced , $_n_skills_intermediate );

                DB::table('portfolio_expert')->where('id' , $resume->id)->update(
                    array(
                        "skills" => serialize($_skills)
                    )
                );
            }
            
        }
    }
}
