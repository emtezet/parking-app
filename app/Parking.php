<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    public function reservations() {
        return $this->hasMany(Reservation::class);
    }

    public function rents() {
        return $this->hasMany(Rent::class);
    }

    public function prices() {
        return $this->hasMany(PriceList::class);
    }
}
