<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;

class CustomerController extends Controller
{
    // Function Untuk Menampilkan Data Customer
    public function index()
    {
        $customers = customer::get();
        return response()->json([
            'type' => 'success',
            'message' => 'fetch data customer success!',
            'data' => $customers
        ]);
    }
    
    // Function Untuk Menginput Data Customer
    public function store(Request $request)
    {
        $customers = customer::create($request->all());
        return response()->json([
            'type' => 'success',
            'messgae' => 'fetch input data customer success!',
            'data' => $customers
        ]);
    }

    // Function Untuk Mengambil Id Data Customer
    public function show($id)
    {
        $customers = Customer::find($id);

        if(! $customers) {
            return response()->json([
                'message' => 'customer not found'
            ]);
        }

        return $customers;
    }

    // Function Untuk Update Data Customer
    public function update(Request $request, $id)
    {
        $customers = Customer::find($id);

        if($customers) {
            $customers->update($request->all());

            return response()->json([
                'message' => 'fecth update data customer success!'
            ]);
        }

        return response()->json([
            'message' => 'customer not found'
        ], 404);
    }

    // Function Untuk Delete Data Customer
    public function delete($id)
    {
        $customers = Customer::find($id);

        if($customers) {
            $customers->delete();

            return response()->json([
                'message' => 'fetch delete data customer success!'
            ]);
        }

        return response()->json([
            'message' => 'customer not found'
        ], 404);
    }
}
