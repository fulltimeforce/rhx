<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
        [
            'name' => "Gerson",
            'email' => 'gerzon.tazza@fulltimeforce.com',
            'password' => bcrypt('bestcamp19'),
        ],[
            'name' => "Shenia",
            'email' => 'shenia.gordillo@fulltimeforce.com',
            'password' => bcrypt('freshgrass28'),
        ]
        ]);
    }
}
