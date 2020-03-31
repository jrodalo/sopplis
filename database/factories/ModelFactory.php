<?php

use Faker\Generator as Faker;

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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'api_token' => str_random(60),
    ];
});

$factory->define(App\Cart::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'slug' => str_random(10),
    ];
});

$factory->define(App\Item::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'count' => $faker->numberBetween(1, 100),
        'visible' => $faker->numberBetween(0, 1),
        'done' => $faker->numberBetween(0, 1),
    ];
});
