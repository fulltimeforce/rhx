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
                'name' => "Kevin",
                'email' => 'ceo@fulltimeforce.com',
                'password' => bcrypt('3mptyShape10'),
                // 'created_at' => date(),
            ],[
                'name' => "Vanessa",
                'email' => 'vanessa.canchanya@fulltimeforce.com',
                'password' => bcrypt('sweetfawn94'),
                // 'created_at' => date(),
            ],[
                'name' => "Gerzon",
                'email' => 'gerzon.tazza@fulltimeforce.com',
                'password' => bcrypt('bestcamp19'),
                // 'created_at' => date(),
            ],[
                'name' => "Shenia",
                'email' => 'shenia.gordillo@fulltimeforce.com',
                'password' => bcrypt('freshgrass28'),
                // 'created_at' => date(),
            ],[
                'name' => "Edwin",
                'email' => 'edwin.soria@fulltimeforce.com',
                'password' => bcrypt('me$sySnail21'),
                // 'created_at' => date(),
            ]
        ]);
    }
}
