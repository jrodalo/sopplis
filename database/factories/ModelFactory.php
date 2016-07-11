<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
    ];
});

$factory->define(App\Cart::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'slug' => str_random(10),
    ];
});

$factory->define(App\Item::class, function (Faker\Generator $faker) {
    return [
    	'cart_id' => function () {
            return factory(App\Cart::class)->create()->id;
        },
        'name' => $faker->name,
        'count' => $faker->numberBetween(1,100),
        'visible' => $faker->numberBetween(0,1),
        'done' => $faker->numberBetween(0,1),
    ];
});
