<?php

namespace App\Http\Controllers;

use App\Parking;
use App\Reservation;
use Illuminate\Http\Request;
use App\Http\Resources\Reservation as ReservationResource;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $reservations = Reservation::where('valid_to', '>', new \DateTime('now',  new \DateTimeZone('Europe/Warsaw')))
            ->orderBy('valid_to', 'DESC')
            ->get();

        return ReservationResource::collection($reservations);
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

        $reservation = new Reservation();

        $parking = Parking::where('id', intval($request->input('parking_id')))->first();

        $reservation->registration_number = $request->input('registration_number');
        $reservation->parking_id = $parking->id;
        $reservation->valid_to = new \DateTime('+ 1hour', new \DateTimeZone('Europe/Warsaw'));

        $reservation->save();

        if($reservation->save()) {

            return json_encode($reservation->valid_to->format('Y-m-d H:i:s'));

            //return new ReservationResource($reservation);
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
        $reservation = Reservation::findOrFail($id);

        if($reservation->delete()) {
            return new ReservationResource($reservation);
        }
    }
}
