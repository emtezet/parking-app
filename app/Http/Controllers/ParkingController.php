<?php

namespace App\Http\Controllers;

use App\Http\Resources\Parking as ParkingResource;
use App\Parking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ParkingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        $parkings = Parking::orderBy('created_at', 'DESC')->get();

        return ParkingResource::collection($parkings);
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

        Validator::make($request->all(), [
            'name' => 'required|max:20',
            'address' => 'required|max:100',
            'places_amount' => 'required|integer',
        ], [
            'name.required' => 'Nazwa parkingu jest wymagana!',
            'name.max' => 'Nazwa parkingu nie może być dłuższa niż 20 znaków!',
            'address.required' => 'Adres parkingu jest wymagany!',
            'address.max' => 'Adres parkingu nie może być dłuższy niż 100 znaków!',

            'places_amount.required' => 'Liczba miejsc jest wymagana!',
            'places_amount.integer' => 'Liczba miejsc musi być liczbą!',
        ])->validate();

        $parking = $request->isMethod('put') ? Parking::findOrFail($request->parking_id) : new Parking();

        $parking->id = $request->input('parking_id');
        $parking->name = $request->input('name');
        $parking->address = $request->input('address');
        $parking->places_amount = $request->input('places_amount');

        if($parking->save()) {
            return new ParkingResource($parking);
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

        $parking = Parking::findOrFail($id);

        if($parking->delete()) {
            return new ParkingResource($parking);
        }

    }
}
