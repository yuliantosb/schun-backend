<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Supplier;
use App\Helpers\Pages;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $ordering = json_decode($request->ordering);
        $supplier = Supplier::where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('email', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('phone_number', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('address', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy($ordering->type, $ordering->sort)
                            ->paginate((int)$request->perpage);

        $pages = Pages::generate($supplier);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $supplier->total(),
                'per_page' => $supplier->perPage(),
                'current_page' => $supplier->currentPage(),
                'last_page' => $supplier->lastPage(),
                'from' => $supplier->firstItem(),
                'to' => $supplier->lastItem(),
                'pages' => $pages,
                'data' => $supplier->all()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);

        $supplier = new Supplier;
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->phone_number = $request->phone_number;
        $supplier->address = $request->address;
        $supplier->save();

        return response()->json([
            'type' => 'success',
            'message' => 'supplier saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $supplier = Supplier::find($id);
        return response()->json([
            'type' => 'success',
            'data' => $supplier
        ], 200);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required'
        ]);

        $supplier = Supplier::find($id);
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->phone_number = $request->phone_number;
        $supplier->address = $request->address;
        $supplier->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Supplier updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        $supplier->deleted_by = auth()->user()->id;
        $supplier->save();
        $supplier->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'supplier deleted successfully'
        ], 201);

    }

    public function supplier(Request $request)
    {
        
        $suppliers = Supplier::where('name', 'like', '%'.$request->name.'%')
                ->get();
        
        return response()->json([
            'type' => 'success',
            'data' => $suppliers
        ], 200);
    }
}
