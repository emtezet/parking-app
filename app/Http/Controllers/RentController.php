<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\PriceList;
use App\Rent;
use App\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Rent as RentResource;
use App\Http\Resources\Reservation as ReservationResource;
use Illuminate\Support\Facades\Response;


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

        if ($rent->save()) {
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

        if ($rent->save()) {
            return new RentResource($rent);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function createFromReservation($id) {

        $reservation = Reservation::findOrFail($id);

        $vehicle = Vehicle::where('registration_number', $reservation->registration_number)->first();


        $rent = new Rent();
        $rent->parking_id = $reservation->parking_id;
        $rent->vehicle_id = $vehicle->id;
        $rent->start_time = new \DateTime('now', new \DateTimeZone('Europe/Warsaw'));

        if ($reservation->delete() && $rent->save()) {
            return new ReservationResource($reservation);
        }
    }

    /**
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getReport(Request $request) {

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $vehicle = Vehicle::where('id', $request->input('vehicle_id'))->first();

        $errors = [];

        if(!$dateFrom) {
            $errors[] = 'Data od jest wymagana!';
        }

        if(!$dateTo) {
            $errors[] = 'Data do jest wymagana!';
        }

        if(!$request->input('vehicle_id')) {
            $errors[] = 'Wybierz pojazd!';
        }

        if($dateFrom && $dateTo && $dateFrom > $dateTo) {
            $errors[] = 'Data od nie może być większa od Daty do!';
        }

        if($request->input('vehicle_id') && !$vehicle) {
            $errors[] = 'Pojazd nie istnieje w systemie!';
        }


        if(count($errors)) {
            return response()->json([
                'errors' => [
                    'custom_errors' => $errors
                ]
            ], 422);
        }

        $rents = Rent::where('start_time', '>=', $dateFrom)
            ->where('start_time', '<=', $dateTo)
            ->where('vehicle_id', '=', $vehicle->id)
            ->whereNotNull('price')
            ->orderBy('start_time', 'DESC')
            ->get();

        $price = 0;
        foreach ($rents as $rent) {
            $price += $rent->price;
        }

        $ret = RentResource::collection($rents);

        $ret->with = [
            'price' => sprintf('%.2f', $price)
        ];

        return $ret;
    }

    public function getReportCsv(Request $request) {
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $vehicle = Vehicle::where('id', $request->input('vehicle_id'))->first();

        $errors = [];

        if(!$dateFrom) {
            $errors[] = 'Data od jest wymagana!';
        }

        if(!$dateTo) {
            $errors[] = 'Data do jest wymagana!';
        }

        if(!$request->input('vehicle_id')) {
            $errors[] = 'Wybierz pojazd!';
        }

        if($dateFrom && $dateTo && $dateFrom > $dateTo) {
            $errors[] = 'Data od nie może być większa od Daty do!';
        }

        if($request->input('vehicle_id') && !$vehicle) {
            $errors[] = 'Pojazd nie istnieje w systemie!';
        }


        if(count($errors)) {
            return response()->json([
                'errors' => [
                    'custom_errors' => $errors
                ]
            ], 422);
        }

        $fileName = $dateFrom . '_' . $dateTo . '_' . $vehicle->registration_number . '_rents.csv';
        $rents = Rent::where('start_time', '>=', $dateFrom)
            ->where('start_time', '<=', $dateTo)
            ->where('vehicle_id', '=', $vehicle->id)
            ->whereNotNull('price')
            ->orderBy('start_time', 'DESC')
            ->get();

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $columns = array('date_from', 'date_to', 'parking', 'price');

        $callback = function () use ($rents, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($rents as $rent) {
                fputcsv($file, [
                    $rent->start_time,
                    $rent->end_time,
                    $rent->parking->name,
                    $rent->price,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
