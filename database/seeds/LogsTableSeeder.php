<?php

use Illuminate\Database\Seeder;

use App\Log;
use App\Position;

class LogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Log::class,50)->create();
    }
}
