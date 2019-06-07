<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shippingfee;
use App\Helpers\Pages;

class ShippingfeeController extends Controller
{
    public function index(Request $request)
    {
        $shippingfee = Shippingfee::where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('price', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy('name')
                            ->paginate((int)$request->perpage);

        $pages = Pages::generate($shippingfee);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $shippingfee->total(),
                'per_page' => $shippingfee->perPage(),
                'current_page' => $shippingfee->currentPage(),
                'last_page' => $shippingfee->lastPage(),
                'from' => $shippingfee->firstItem(),
                'to' => $shippingfee->lastItem(),
                'pages' => $pages,
                'data' => $shippingfee->all()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $shippingfee = new Shippingfee;
        $shippingfee->name = $request->name;
        $shippingfee->price = $request->price;
        $shippingfee->save();

        return response()->json([
            'type' => 'success',
            'message' => 'shippingfee saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $shippingfee = Shippingfee::find($id);
        return response()->json([
            'type' => 'success',
            'data' => $shippingfee
        ], 200);
    }

    public function update($id, Request $request)
    {
        $shippingfee = Shippingfee::find($id);
        $shippingfee->name = $request->name;
        $shippingfee->price = $request->price;
        $shippingfee->save();

        return response()->json([
            'type' => 'success',
            'message' => 'shippingfee updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $shippingfee = Shippingfee::find($id);
        $shippingfee->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'shippingfee deleted successfully'
        ], 201);

    }
}
