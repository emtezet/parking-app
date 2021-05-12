<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Parking extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        //return parent::toArray($request);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'places_amount' => $this->places_amount,
            'active_rents_count' => $this->activeRents()->count(),
            'active_reservations_count' => $this->activeReservations()->count(),
            'free_places' =>  $this->places_amount - ($this->activeRents()->count() + $this->activeReservations()->count())
        ];

    }
}
