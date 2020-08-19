<?php

use Illuminate\Database\Seeder;
use App\Position;
use Illuminate\Support\Str;

class PositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Position::class, 10)->create();
    }
}
