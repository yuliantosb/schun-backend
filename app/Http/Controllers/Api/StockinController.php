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
            'message' => 'fetch data stock in success!',
            'data' => $stockins
        ]);
    }

    // Function Untuk Menginput Data Stock In
    public function store(Request $request)
    {
        $stockins = Stockin::create($request->all());
        return response()->json([
            'type' => 'success',
            'message' => 'fetch input data stock in success!',
            'data' => $stockins
        ]);
    }

    // Function Untuk Mengambil Id Data Stock In
    public function show($id)
    {
        $stockins = Stockin::find($id);

        if(! $stockins) {
            return response()->json([
                'message' => 'stockin not found'
            ]);
        }

        return $stockins;
    }

    // Function Untuk Mengupdate Data Stock In
    public function update(Request $request, $id)
    {
        $stockins = Stockin::find($id);

        if($stockins) {
            $stockins->update($request->all());

            return response()->json([
                'message' => 'fetch update data stock in success!'
            ]);
        }

        return response()->json([
            'message' => 'stock in not found'
        ], 404);
    }

    // Function Untuk Mengdelete Data Stock In
    public function delete($id)
    {
        $stockins = Stockin::find($id);

        if($stockins) {
            $stockins->delete();

            return response()->json([
                'message' => 'fetch delete data stock in success!'
            ]);
        }

        return response()->json([
            'message' => 'stock in not found!'
        ], 404);
    }
}
