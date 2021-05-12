<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Vehicle;
use App\VehicleType;
use Faker\Generator as Faker;

$factory->define(Vehicle::class, function (Faker $faker) {

    $vehicleTypesIds = VehicleType::all()->pluck('id')->toArray();

    return [
        'registration_number' => $faker->randomNumber(5),
        'vehicle_type_id' => $faker->randomElement($vehicleTypesIds)
    ];
});
