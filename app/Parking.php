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

    public function activeRents() {
        return $this->hasMany(Rent::class)->where('price', '=', null);
    }

    public function activeReservations() {
        return $this->hasMany(Reservation::class)->where('valid_to', '>', new \DateTime('now',  new \DateTimeZone('Europe/Warsaw')));
    }
}
