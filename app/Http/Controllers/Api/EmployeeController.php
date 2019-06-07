<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use App\Helpers\Pages;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('date_of_birth', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('place_of_birth', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('hire_date', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('photo', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('address', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('registration_number', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy('name')
                            ->paginate((int)$request->perpage);

        $pages = Pages::generate($employee);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $employee->total(),
                'per_page' => $employee->perPage(),
                'current_page' => $employee->currentPage(),
                'last_page' => $employee->lastPage(),
                'from' => $employee->firstItem(),
                'to' => $employee->lastItem(),
                'pages' => $pages,
                'data' => $employee->all()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $employee = new Employee;
        $employee->date_of_birth = $request->date_of_birth;
        $employee->place_of_birth = $request->place_of_birth;
        $employee->hire_date = $request->hire_date;
        $employee->photo = $request->photo;
        $employee->address = $request->address;
        $employee->registration_number = $request->registration_number;
        $employee->save();

        return response()->json([
            'type' => 'success',
            'message' => 'employee saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $employee = Employee::find($id);
        return response()->json([
            'type' => 'success',
            'data' => $employee
        ], 200);
    }

    public function update($id, Request $request)
    {
        $employee = Employee::find($id);
        $employee->date_of_birth = $request->date_of_birth;
        $employee->place_of_birth = $request->place_of_birth;
        $employee->hire_date = $request->hire_date;
        $employee->photo = $request->photo;
        $employee->address = $request->address;
        $employee->registration_number = $request->registration_number;
        $employee->save();

        return response()->json([
            'type' => 'success',
            'message' => 'employee updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        $employee->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'employee deleted successfully'
        ], 201);

    }
}
