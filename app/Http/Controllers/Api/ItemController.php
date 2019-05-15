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
            'message' => 'fetch data item success!',
            'data' => $items
        ]);
    }

    // Function Untuk Menginput Data Item
    public function store(Request $request)
    {
        $items = Item::create($request->all());
        return response()->json([
            'type' => 'success',
            'message' => 'fetch input data item success!',
            'data' => $items
        ]);
    }

    // Function Untuk Mengambil Id Data Item
    public function show($id)
    {
        $items = Item::find($id);

        if(! $items) {
            return response()->json([
                'message' => 'items not found'
            ]);
        }

        return $items;
    }

    // Function Untuk Mengupdate Data Item
    public function update(Request $request, $id)
    {
        $items = Item::find($id);

        if($items) {
            $items->update($request->all());

            return response()->json([
                'message' => 'fetch update data item success!'
            ]);
        }

        return response()->json([
            'message' => 'items not found'
        ], 404);
    }

    // Function Untuk Mendelete Data Item
    public function delete($id)
    {
        $items = Item::find($id);

        if($items) {
            $items->delete();

            return response()->json([
                'message' => 'fetch delete data item success!'
            ]);
        }

        return response()->json([
            'message' => 'items not found'
        ], 404);
    }
    
}
