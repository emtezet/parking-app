<?php

use App\Parking;
use App\PriceList;
use App\VehicleType;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;


class PriceListsTableSeeder extends Seeder
{
    /**
     * The current Faker instance.
     *
     * @var Faker
     */
    protected $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct() {
        $this->faker = $this->withFaker();
    }

    /**
     * Get a new Faker instance.
     *
     * @return Faker
     */
    protected function withFaker() {
        return Container::getInstance()->make(Faker::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $parkings = Parking::all();
        $vehicleTypes = VehicleType::all();

        foreach($parkings as $parking) {
            foreach($vehicleTypes as $vehicleType) {
                PriceList::create([
                    'parking_id' => $parking->id,
                    'vehicle_type_id' => $vehicleType->id,
                    'price_per_hour' => $this->faker->randomFloat(2, 1, 10)
                ]);
            }
        }

    }
}
