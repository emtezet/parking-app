<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    public function vehicleType() {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }

    public function rents() {
        return $this->hasMany(Rent::class);
    }
}
