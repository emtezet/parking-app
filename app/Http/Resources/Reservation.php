<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Reservation extends JsonResource
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
            'parking_id' => $this->parking->id,
            'parking_name' => $this->parking->name,
            'registration_number' => $this->registration_number,
            'valid_to' => $this->valid_to
        ];
    }
}
