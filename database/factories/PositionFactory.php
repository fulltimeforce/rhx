<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Position;
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

$factory->define(Position::class, function (Faker $faker) {
    return [
        'id' => Str::random(16),
        'name' => $name = "Position {$faker->word}",
        'description' => $faker->paragraph(1),
        'slug' => preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($name)),
        'technology_advan' => 'english_speaking,php,mysql'
    ];
});