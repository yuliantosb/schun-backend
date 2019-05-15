<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\StockIn;

class StockinController extends Controller
{
    // Function Untuk Menampilkan Data StockIn
    public function index()
    {
        $stockins = Stockin::with(['Items', 'Items.Uom'])->get();
        return response()->json([
            'type' => 'success',
            'message' => 'fetch Data Stock In Success',
            'data' => $stockins
        ]);
    }
}
