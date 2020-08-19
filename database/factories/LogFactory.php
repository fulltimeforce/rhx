<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Log;
use App\Position;
use App\Expert;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
$factory->define(Log::class, function (Faker $faker) {
    $platforms = collect(LOG::getPlataforms())->pluck('id')->toArray();
    $positions = Position::all()->pluck('id')->toArray();
    $experts = Expert::all()->pluck('id')->toArray();
    return [
        'position_id' => $faker->randomElement($positions),
        'expert_id' => $faker->randomElement($experts),
        'platform' => $faker->randomElement($platforms),
        'link' => $faker->url
    ];
});