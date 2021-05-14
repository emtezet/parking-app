<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Rent extends JsonResource
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
            'vehicle_id' => $this->vehicle->id,
            'vehicle_registration_number' => $this->vehicle->registration_number,
            'start_time' => $this->start_time,
            'price' => $this->price,
            'end_time' => $this->end_time
        ];
    }

    public function with($request) {
        return [
            'price' => 'test'
        ];
    }

//    public function with($request) {
//
//        $rents = $this->collection;
//
//        foreach($rents as $rent) {
//            $price += $rent->price;
//        }
//
//        return [
//            'report' => [
//                'priceSum' => $price
//            ]
//        ];
//    }
}
