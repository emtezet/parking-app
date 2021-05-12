<?php

use App\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        VehicleType::create(['name' => 'Motocykl']);
        VehicleType::create(['name' => 'Samochód osobowy']);
        VehicleType::create(['name' => 'Van']);
        VehicleType::create(['name' => 'Samochód ciężarowy']);
        VehicleType::create(['name' => 'Autobus']);
    }
}
