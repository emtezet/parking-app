<?php

use App\Parking;
use App\PriceList;
use App\Rent;
use App\Vehicle;
use Illuminate\Container\Container;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;


class RentsTableSeeder extends Seeder
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

        $vehiclesIds = Vehicle::all()->pluck('id')->toArray();
        $parkingsIds = Parking::all()->pluck('id')->toArray();


        for ($i = 1; $i <= 50; $i++) {

            $startTime = $this->faker->dateTimeBetween('-30 days', '-1 day', 'Europe/Warsaw');
            $endTime = $this->faker->dateTimeBetween($startTime->format('Y-m-d H:i:s'), '-1 day', 'Europe/Warsaw');

            $datesDiff = sprintf("%.2f", ($endTime->getTimestamp() - $startTime->getTimestamp()) / 3600);

            /** @var Vehicle $vehicle */
            $vehicle = Vehicle::where('id', $vehiclesIds[array_rand($vehiclesIds, 1)])->first();

            /** @var Parking $vehicle */
            $parking = Parking::where('id', $parkingsIds[array_rand($parkingsIds, 1)])->first();

            /** @var PriceList $priceList */
            $priceList = PriceList::where('parking_id', '=', $parking->id)
                ->where('vehicle_type_id', '=', $vehicle->vehicleType->id)
                ->first();

            Rent::create([
                'start_time' => $startTime,
                'end_time' => $i % 3 == 0 ? null : $endTime,
                'vehicle_id' => $vehicle->id,
                'parking_id' => $parking->id,
                'price' => $i % 3 == 0 ? null : sprintf("%.2f", $priceList->price_per_hour * $datesDiff),
            ]);

        }

    }
}
