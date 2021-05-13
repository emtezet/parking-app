<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PriceList extends JsonResource
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
            'price_per_hour' => $this->price_per_hour,
            'vehicle_type_id' => $this->vehicleType->id,
            'parking_id' => $this->parking->id,
            'vehicle_type_name' => $this->vehicleType->name,
            'parking_name' => $this->parking->name
        ];
    }
}
