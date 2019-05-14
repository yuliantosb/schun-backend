<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StockOut;

class StockoutController extends Controller
{
    // Function Untuk Menampilkan Data Stock Out
    public function index()
    {
        $stockouts = Stockout::get();
        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock out success',
            'data' => $stockouts
        ]);
    }
}
