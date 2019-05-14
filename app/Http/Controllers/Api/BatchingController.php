<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Batching;

class BatchingController extends Controller
{   
    // Function Untuk Menampilkan Data Batching Plan
    public function index()
    {
        $batchings = Batching::get();
        return response()->json([
            'type' => 'success',
            'message' => 'fetch data batching success!',
            'data' => $batchings
        ]);
    }
}
