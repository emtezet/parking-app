<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    public function parking() {
        return $this->belongsTo(Parking::class, 'parking_id');
    }

    public function vehicle() {
        return $this->belongsTo(Parking::class, 'vehicle_id');
    }
}
