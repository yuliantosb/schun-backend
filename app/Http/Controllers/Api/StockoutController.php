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
            'message' => 'fetch data stock out success!',
            'data' => $stockouts
        ]);
    }

    // Function Untuk Menginput Data Stock Out
    public function store(Request $request)
    {
        $stockouts = Stockout::create($request->all());
        return response()->json([
            'type' => 'success',
            'message' => 'fetch input data stock out success!',
            'data' => $stockouts
        ]);
    }

    // Function Untuk Mengambil Id Data Stock Out
     public function show($id)
     {
         $stockouts = Stockout::find($id);
 
         if(! $stockouts) {
             return response()->json([
                 'message' => 'stock out not found'
             ]);
         }
 
         return $stockouts;
     }
 
     // Function Untuk Mengupdate Data Stock Out
     public function update(Request $request, $id)
     {
         $stockouts = Stockout::find($id);
 
         if($stockouts) {
             $stockouts->update($request->all());
 
             return response()->json([
                 'message' => 'fetch update data stock out success!'
             ]);
         }
 
         return response()->json([
             'message' => 'stock out not found'
         ], 404);
     }
     
    //  Function Untuk Mengdelete Data Stock In
    public function delete($id)
    {
        $stockouts = Stockout::find($id);

        if($stockouts) {
            $stockouts->delete();

            return response()->json([
                'message' => 'fetch delete data stock out success!'
            ]);
        }

        return response()->json([
            'message' => 'stock out not found!'
        ], 404);
    }
}
