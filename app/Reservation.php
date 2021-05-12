<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public function parking() {
        return $this->belongsTo(Parking::class, 'parking_id');
    }
}
