<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Helpers\Pages;

class CustomerController extends Controller
{
    // Function Untuk Menampilkan Data Customer
    public function index(Request $request)
    {
        $ordering = json_decode($request->ordering);
        $customers = Customer::where(function($where) use ($request){
    
            if (!empty($request->keyword)) {

                $where->where('name', 'like', '%'.$request->keyword.'%')
                ->orWhere('email', 'like', '%'.$request->keyword.'%')
                ->orWhere('address', 'like', '%'.$request->keyword.'%')
                ->orWhere('phone_number', 'like', '%'.$request->keyword.'%');
            }
        })

        ->orderBy($ordering->type, $ordering->sort)
        ->paginate((int)$request->perpage);

        $pages = Pages::generate($customers);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
            'total' => $customers->total(),
            'per_page' => $customers->perPage(),
            'current_page' => $customers->currentPage(),
            'last_page' => $customers->lastPage(),
            'from' => $customers->firstItem(),
            'to' => $customers->lastItem(),
            'pages' => $pages,
            'data' => $customers->all()
            ]
            ], 200);
    }
    
    // Function Untuk Menginput Data Customer
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);

        
        $customer = new Customer;
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone_number = $request->phone_number;
        $customer->place_of_birth = $request->place_of_birth;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->address = $request->address;
        $customer->save();

        return response()->json([
            'type' => 'success',
            'message' => 'save input data customer success!'
        ], 201);
    }

    // Function Untuk Mengambil Id Data Customer
    public function show($id)
    {
        $customers = Customer::find($id);

        if(! $customers) {
            return response()->json([
                'type' => 'error',
                'message' => 'customer not found'
            ], 500);
        }

        return response()->json([
            'type' => 'success',
            'data' => $customers
        ], 200);
    }

    // Function Untuk Update Data Customer
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);

        
        $customer = Customer::find($id);
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone_number = $request->phone_number;
        $customer->place_of_birth = $request->place_of_birth;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->address = $request->address;
        $customer->save();

        return response()->json([
            'type' => 'success',
            'message' => 'update input data customer success!'
        ], 201);
    }

    // Function Untuk Delete Data Customer
    public function destroy($id)
    {
        $customers = Customer::find($id);
        $customer->deleted_by = auth()->user()->id;
        $customer->save();

        if($customers) {
            $customers->delete();

            return response()->json([
                'type' => 'success',
                'message' => 'fetch delete data customer success!'
            ], 200);
        }

        return response()->json([
            'message' => 'customer not found'
        ], 404);
    }
}
