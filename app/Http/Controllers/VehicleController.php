<?php

namespace App\Http\Controllers;

use App\Vehicle;
use Illuminate\Http\Request;
use App\Http\Resources\Vehicle as VehicleResource;
use Illuminate\Support\Facades\Validator;


class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $vehicles = Vehicle::orderBy('created_at', 'DESC')->get();

        return VehicleResource::collection($vehicles);
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
            'registration_number' => 'required|unique:App\Vehicle,registration_number|max:5',
            'vehicle_type_id' => 'required',
        ], [
            'registration_number.required' => 'Niepoprawnie wypełniony nr rejestracyjny!',
            'registration_number.unique' => 'Pojazd o tym numerze rejestracyjnym już istnieje!',
            'registration_number.max' => 'za długi!',
            'vehicle_type_id.required' => 'Wybierz typ pojazdu!'
        ])->validate();

        //if($validator->fails()) {
            //return $validator->errors()->toJson();
        //} else {
            $vehicle = $request->isMethod('put') ? Vehicle::findOrFail($request->vehicle_id) : new Vehicle();

            $vehicle->id = $request->input('vehicle_id');
            $vehicle->registration_number = $request->input('registration_number');
            $vehicle->vehicle_type_id = $request->input('vehicle_type_id');

            if($vehicle->save()) {
                return new VehicleResource($vehicle);
            }
        //}
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
        $vehicle = Vehicle::findOrFail($id);

        if($vehicle->delete()) {
            return new VehicleResource($vehicle);
        }
    }
}
