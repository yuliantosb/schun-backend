<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Sales;
use App\Helpers\Pages;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $sales = Sales::where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('description', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('price', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy('name')
                            ->paginate((int)$request->perpage);

        $pages = Pages::generate($sales);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $sales->total(),
                'per_page' => $sales->perPage(),
                'current_page' => $sales->currentPage(),
                'last_page' => $sales->lastPage(),
                'from' => $sales->firstItem(),
                'to' => $sales->lastItem(),
                'pages' => $pages,
                'data' => $sales->all()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $sales = new Sales;
        $sales->name = $request->name;
        $sales->description = $request->description;
        $sales->price = $request->price;
        $sales->save();

        return response()->json([
            'type' => 'success',
            'message' => 'sales saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $sales = Sales::find($id);
        return response()->json([
            'type' => 'success',
            'data' => $sales
        ], 200);
    }

    public function update($id, Request $request)
    {
        $sales = Sales::find($id);
        $sales->name = $request->name;
        $sales->description = $request->description;
        $sales->price = $request->price;
        $sales->save();

        return response()->json([
            'type' => 'success',
            'message' => 'sales updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $sales = Sales::find($id);
        $sales->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'sales deleted successfully'
        ], 201);

    }
}
