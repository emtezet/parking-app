<?php

namespace App\Http\Controllers;

use App\PriceList;
use Illuminate\Http\Request;
use App\Http\Resources\PriceList as PriceListResource;

class PriceListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $priceLists = PriceList::orderBy('created_at', 'DESC')->get();

        return PriceListResource::collection($priceLists);
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
        $priceList = $request->isMethod('put') ? PriceList::findOrFail($request->price_list_id) : new PriceList();

        $priceList->id = $request->input('price_list_id');
        $priceList->price_per_hour = $request->input('price_per_hour');
        $priceList->vehicle_type_id = $request->input('vehicle_type_id');
        $priceList->parking_id = $request->input('parking_id');

        if($priceList->save()) {
            return new PriceListResource($priceList);
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
        $priceList = PriceList::findOrFail($id);

        if($priceList->delete()) {
            return new PriceListResource($priceList);
        }
    }
}
