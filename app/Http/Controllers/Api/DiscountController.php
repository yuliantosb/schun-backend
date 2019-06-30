<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Pages;
use App\Discount;

class DiscountController extends Controller
{
    // Function Untuk Menampilkan Data Discount
    public function index(Request $request)
    {
        $ordering = json_decode($request->ordering);
        $discount = Discount::where(function($where) use ($request){
    
            if (!empty($request->keyword)) {

                $where->where('name', 'like', '%'.$request->keyword.'%')
                ->orWhere('amount', 'like', '%'.$request->keyword.'%')
                ->orWhere('description', 'like', '%'.$request->keyword.'%');
            }
        })

        ->orderBy($ordering->type, $ordering->sort)
        ->paginate((int)$request->perpage);

        $pages = Pages::generate($discount);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
            'total' => $discount->total(),
            'per_page' => $discount->perPage(),
            'current_page' => $discount->currentPage(),
            'last_page' => $discount->lastPage(),
            'from' => $discount->firstItem(),
            'to' => $discount->lastItem(),
            'pages' => $pages,
            'data' => $discount->all()
            ]
            ], 200);
    }
    
    // Function Untuk Menginput Data Discount
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required'
        ]);

        
        $discount = new Discount;
        $discount->name = $request->name;
        $discount->amount = str_replace(',', '', $request->amount);
        $discount->description = $request->description;
        $discount->type = !empty($request->type) ? $request->type : 'fixed';
        $discount->save();

        return response()->json([
            'type' => 'success',
            'message' => 'save input data discoun$discount success!'
        ], 201);
    }

    // Function Untuk Mengambil Id Data Discount
    public function show($id)
    {
        $discount = Discount::find($id);

        if(! $discount) {
            return response()->json([
                'type' => 'error',
                'message' => 'discoun$discount not found'
            ], 500);
        }

        return response()->json([
            'type' => 'success',
            'data' => $discount
        ], 200);
    }

    // Function Untuk Update Data Discount
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'amount' => 'required'
        ]);

        
        $discount = Discount::find($id);
        $discount->name = $request->name;
        $discount->amount = str_replace(',', '', $request->amount);
        $discount->description = $request->description;
        $discount->type = !empty($request->type) ? $request->type : 'fixed';
        $discount->save();

        return response()->json([
            'type' => 'success',
            'message' => 'update input data discoun$discount success!'
        ], 201);
    }

    // Function Untuk Delete Data Discount
    public function destroy($id)
    {
        $discount = Discount::find($id);
        $discount->deleted_by = auth()->user()->id;
        $discount->save();

        if($discount) {
            $discount->delete();

            return response()->json([
                'type' => 'success',
                'message' => 'fetch delete data discoun$discount success!'
            ], 200);
        }

        return response()->json([
            'message' => 'discoun$discount not found'
        ], 404);
    }

}
