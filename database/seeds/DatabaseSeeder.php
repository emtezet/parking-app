<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call(UserTableSeeder::class);
        $this->call(VehicleTypesTableSeeder::class);
        $this->call(ParkingsTableSeeder::class);
        $this->call(PriceListsTableSeeder::class);
        $this->call(VehiclesTableSeeder::class);
        $this->call(RentsTableSeeder::class);
        $this->call(ReservationsTableSeeder::class);
    }
}
