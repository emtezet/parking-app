<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', function (Request $request) {
    $data = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response([
            'message' => ['Niepoprawne dane logowania']
        ], 404);
    }

    $token = $user->createToken('my-app-token')->plainTextToken;

    $response = [
        'user' => $user,
        'token' => $token
    ];

    return response($response, 201);
});

//////////////////////
//Parking Controller//
//////////////////////

//List of parkings with free places
Route::get('/parkings', 'ParkingController@index');

//Create new parking
Route::post('/parking', 'ParkingController@store');

//Update parking
Route::put('/parking', 'ParkingController@store');

//Delete parking
Route::delete('/parking/{id}', 'ParkingController@destroy');

//////////////////////////
//VehicleType Controller//
//////////////////////////

//List
Route::get('/vehicle_types', 'VehicleTypeController@index');

//Create
Route::post('/vehicle_type', 'VehicleTypeController@store');

//Update
Route::put('/vehicle_type', 'VehicleTypeController@store');

//Delete
Route::delete('/vehicle_type/{id}', 'VehicleTypeController@destroy');

//////////////////////////
//Reservation Controller//
//////////////////////////

//List
Route::get('/reservations', 'ReservationController@index');

//Create
Route::post('/reservation', 'ReservationController@store');

//Update
Route::put('/reservation', 'ReservationController@store');

//Delete
Route::delete('/reservation/{id}', 'ReservationController@destroy');

//////////////////////
//Vehicle Controller//
//////////////////////

//List
Route::get('/vehicles', 'VehicleController@index');

//Create
Route::post('/vehicle', 'VehicleController@store');

//Update
Route::put('/vehicle', 'VehicleController@store');

//Delete
Route::delete('/vehicle/{id}', 'VehicleController@destroy');
