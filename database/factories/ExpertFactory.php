<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

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

$factory->define(Expert::class, function (Faker $faker) {
    return [
        'id' => uniqid(),
        'email_address' => $faker->unique()->safeEmail,
        'last_info_update' => date('m/d/Y'),
        'fullname' => "{$faker->firstName()} {$faker->lastName}",
        'file_path' => ''
    ];
});