<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    public function parking() {
        return $this->belongsTo(Parking::class, 'parking_id');
    }

    public function vehicleType() {
        return $this->belongsTo(VehicleType::class, 'vehicle_type_id');
    }
}
