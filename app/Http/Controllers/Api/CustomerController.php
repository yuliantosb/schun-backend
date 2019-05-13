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
}
