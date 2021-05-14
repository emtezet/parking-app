<?php

namespace App\Http\Controllers;

use App\Parking;
use App\PriceList;
use App\Rent;
use Illuminate\Http\Request;
use App\Http\Resources\Rent as RentResource;


class RentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $rents = Rent::whereNull('end_time')
            ->orderBy('start_time', 'DESC')
            ->get();

        return RentResource::collection($rents);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $rent = $request->isMethod('put') ? Rent::findOrFail($request->rent_id) : new Rent();

        $rent->id = $request->input('rent_id');
        $rent->parking_id = $request->input('parking_id');
        $rent->vehicle_id = $request->input('vehicle_id');
        $rent->start_time = new \DateTime('now', new \DateTimeZone('Europe/Warsaw'));

        if($rent->save()) {
            return new RentResource($rent);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $rent = Rent::findOrFail($id);

        $rent->end_time = new \DateTime('now', new \DateTimeZone('Europe/Warsaw'));

        $datesDiff = sprintf("%.2f", ($rent->end_time->getTimestamp() - strtotime($rent->start_time)) / 3600);

        /** @var PriceList $priceList */
        $priceList = PriceList::where('parking_id', '=', $rent->parking->id)
            ->where('vehicle_type_id', '=', $rent->vehicle->vehicleType->id)
            ->first();

        $rent->price = sprintf("%.2f", $priceList->price_per_hour * $datesDiff);

        if($rent->save()) {
            return new RentResource($rent);
        }
    }
}