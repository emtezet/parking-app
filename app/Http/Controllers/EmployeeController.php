<?php

namespace App\Http\Controllers;

use App\Parking;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Resources\Employee as EmployeeResource;
use Illuminate\Http\Resources\Json\JsonResource as ParkingResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $employees = User::orderBy('created_at', 'DESC')->get();

        return EmployeeResource::collection($employees);
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
            'name' => [
                'required',
                'max:50'
            ],
            'email' => [
                'required',
                'email:rfc',
                Rule::unique('users')->ignore($request->employee_id)
            ],
            'role' => [
                'required',
            ]
        ], [
            'name.required' => 'Imię i nazwisko są wymagane!',
            'name.max' => 'Imię i nazwisko nie mogą być dłuższe niż 50 znaków!',

            'email.required' => 'Email jest wymagany!',
            'email.email' => 'Niepoprawny adres email!',
            'email.unique' => 'Adres email jest już przypisany do innego pracownika!',

            'role.required' => 'Wybierz rolę pracownika!'
        ])->validate();

        $user = $request->isMethod('put') ? User::findOrFail($request->employee_id) : new User();

        $user->id = $request->input('employee_id');
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        $user->email_verified_at = now();
        $user->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $user->remember_token = Str::random(10);

        if($user->save()) {
            return new EmployeeResource($user);
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

        $user = User::findOrFail($id);

        if($user->delete()) {
            return new EmployeeResource($user);
        }
    }
}
