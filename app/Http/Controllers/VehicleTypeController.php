<?php

namespace App\Http\Controllers;

use App\Http\Resources\VehicleType as VehicleTypeResource;
use App\VehicleType;
use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $vehicleTypes = VehicleType::orderBy('created_at', 'DESC')->get();

        return VehicleTypeResource::collection($vehicleTypes);
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
        $vehicleType = $request->isMethod('put') ? VehicleType::findOrFail($request->vehicle_type_id) : new VehicleType();

        $vehicleType->id = $request->input('vehicle_type_id');
        $vehicleType->name = $request->input('name');

        if($vehicleType->save()) {
            return new VehicleTypeResource($vehicleType);
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
        $vehicleType = VehicleType::findOrFail($id);

        if($vehicleType->delete()) {
            return new VehicleTypeResource($vehicleType);
        }
    }
}
