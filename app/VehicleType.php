<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    public function vehicles() {
        return $this->hasMany(Vehicle::class);
    }

    public function prices() {
        return $this->hasMany(PriceList::class);
    }
}
