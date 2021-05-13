<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Vehicle extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) {
        return [
            'id' => $this->id,
            'registration_number' => $this->registration_number,
            'vehicle_type_id' => $this->vehicleType->id,
            'vehicle_type_name' => $this->vehicleType->name
        ];
    }
}
