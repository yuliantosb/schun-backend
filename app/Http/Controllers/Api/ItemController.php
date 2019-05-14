<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Item;

class ItemController extends Controller
{   
    // Function Untuk Menampilkan Data Item
    public function index()
    {
        $items = Item::get();
        return response()->json([
            'type' => 'success',
            'message' => 'fetch data item success',
            'data' => $items
        ]);
    }
}
