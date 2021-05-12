<?php

use App\Parking;
use App\Reservation;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Container\Container;



class ReservationsTableSeeder extends Seeder
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
        $parkingsIds = Parking::all()->pluck('id')->toArray();

        for ($i = 1; $i <= 50; $i++) {

            $validTo = $this->faker->dateTimeBetween('-1 hour', '+1 hour', 'Europe/Warsaw');

            /** @var Parking $vehicle */
            $parking = Parking::where('id', $parkingsIds[array_rand($parkingsIds, 1)])->first();


            Reservation::create([
                'parking_id' => $parking->id,
                'registration_number' => $this->faker->randomNumber(5),
                'valid_to' => $validTo
            ]);

        }
    }
}
