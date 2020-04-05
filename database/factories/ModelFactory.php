<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => $faker->password,
        'api_token' => Str::random(60),
    ];
});

$factory->define(App\Models\Cart::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => Str::random(10),
    ];
});

$factory->define(App\Models\Item::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'count' => $faker->numberBetween(1, 100),
        'visible' => $faker->numberBetween(0, 1),
        'done' => $faker->numberBetween(0, 1),
    ];
});
