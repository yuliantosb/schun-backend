<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Employee;
use App\Helpers\Pages;
use App\Role;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employee = User::with(['employee', 'role'])
                            ->whereHas('employee')
                            ->where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('email', 'like', '%'.$request->keyword.'%')
                                        ->orWhereHas('employee', function($whereEmployee) use ($request){
                                            $whereEmployee->where('reg_number', 'like', '%'.$request->keyword.'%')
                                                    ->orWhere('phone_number', 'like', '%'.$request->keyword.'%');
                                        });
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

        $request->validate([
            'name' => 'required',
            'reg_number' => 'required|unique:employees',
            'password' => 'required|confirmed|min:6',
            'email' => 'required|unique:users',
            'role_id' => 'required'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->email_verified_at = Carbon::now();
        $user->remember_token = Str::random(10);
        $user->role_id = $request->role_id;
        $user->save();

        $employee = new Employee;
        $employee->date_of_birth = Carbon::parse($request->date_of_birth)->format('Y-m-d');
        $employee->place_of_birth = $request->place_of_birth;

        if ($request->has('file')) {

            $employee->file = $request->file;
            $employee->photo = $request->photo;
        }
        
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        $employee->reg_number = $request->reg_number;
        $user->employee()->save($employee);

        return response()->json([
            'type' => 'success',
            'message' => 'employee saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $employee = User::with(['employee', 'role'])->find($id);
        return response()->json([
            'type' => 'success',
            'data' => $employee
        ], 200);
    }

    public function update($id, Request $request)
    {

        $request->validate([
            'name' => 'required',
            'reg_number' => 'required|unique:employees,reg_number,'.$id.',user_id',
            'password' => 'confirmed|min:6',
            'email' => 'required|unique:users,email,'.$id.',_id',
            'role_id' => 'required'
        ]);

        $user = User::find($id);
        $user->name = $request->name;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->email = $request->email;
        $user->email_verified_at = Carbon::now();
        $user->remember_token = Str::random(10);
        $user->role_id = $request->role_id;
        $user->save();

        $employee = Employee::firstOrCreate(['user_id' => $id]);
        $employee->date_of_birth = Carbon::parse($request->date_of_birth)->format('Y-m-d');
        $employee->place_of_birth = $request->place_of_birth;

        if ($request->has('file')) {

            $employee->file = $request->file;
            $employee->photo = $request->photo;
        }
        
        $employee->phone_number = $request->phone_number;
        $employee->address = $request->address;
        $employee->reg_number = $request->reg_number;
        $user->employee()->save($employee);

        return response()->json([
            'type' => 'success',
            'message' => 'employee updated successfully'
        ], 201);

    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!empty($user->employee->photo)) {
            
            if (Storage::disk('public')->exists($user->employee->photo)) {
                Storage::disk('public')->delete($user->employee->photo);
            }
        }

        $user->employee()->delete();
        $user->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'employee deleted successfully'
        ], 201);

    }

    public function role(Request $request)
    {
        $roles = Role::where('name', 'like', '%'.$request->name.'%')
                    ->get();
        
        return response()->json([
            'type' => 'success',
            'data' => $roles
        ], 200);
    }
}
