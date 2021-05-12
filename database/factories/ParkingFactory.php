<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Parking;
use Faker\Generator as Faker;

$factory->define(Parking::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'address' => $faker->address,
        'places_amount' => $faker->numberBetween(10, 20)
    ];
});
