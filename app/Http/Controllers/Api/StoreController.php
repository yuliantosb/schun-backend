<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Store;
use App\Helpers\Pages;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $stores = Store::where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('phone', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('address', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy('name')
                            ->paginate((int)$request->perpage);

        $pages = Pages::generate($stores);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $stores->total(),
                'per_page' => $stores->perPage(),
                'current_page' => $stores->currentPage(),
                'last_page' => $stores->lastPage(),
                'from' => $stores->firstItem(),
                'to' => $stores->lastItem(),
                'pages' => $pages,
                'data' => $stores->all()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $store = new Store;
        $store->name = $request->name;
        $store->phone = $request->phone;
        $store->address = $request->address;
        $store->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Store saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $store = Store::find($id);
        return response()->json([
            'type' => 'success',
            'data' => $store
        ], 200);
    }

    public function update($id, Request $request)
    {
        $store = Store::find($id);
        $store->name = $request->name;
        $store->phone = $request->phone;
        $store->address = $request->address;
        $store->save();

        return response()->json([
            'type' => 'success',
            'message' => 'store updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $store = Store::find($id);
        $store->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'Store deleted successfully'
        ], 201);

    }

}
