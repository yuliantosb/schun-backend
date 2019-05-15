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

    // Function Untuk Menginput Data Batching Plan
    public function store(Request $request)
    {
        $batchings = Batching::create($request->all());
        return response()->json([
            'type' => 'success',
            'message' => 'fetch input data batching success!',
            'data' => $batchings
        ]);
    }

    // Function Untuk Mengambil Id Data Batching Plan
    public function show($id)
    {
        $batchings = Batching::find($id);

        if(! $batchings) {
            return response()->json([
                'message' => 'batching not found'
            ]);
        }

        return $batchings;
    }

    // Function Untuk Mengupdate Data Batching Plan
    public function update(Request $request, $id)
    {
        $batchings = Batching::find($id);

        if($batchings) {
            $batchings->update($request->all());

            return response()->json([
                'message' => 'fetch update data batching success!'
            ]);
        }

        return response()->json([
            'message' => 'batching not found'
        ], 404);
    }

    // Function Untuk Delete Data Batching Plan
    public function delete($id)
    {
        $batchings = Batching::find($id);

        if($batchings) {
            $batchings->delete();

            return response()->json([
                'message' => 'fetch delete data batching success!'
            ]);
        }

        return response()->json([
            'message' => 'batching not found'
        ], 404);
    }

}
