<?php

use Illuminate\Database\Seeder;
use App\Expert;

class ExpertsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Expert::class, 100)->create();
    }
}
