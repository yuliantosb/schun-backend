<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Products;
use App\Helpers\Pages;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $products = Products::where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('description', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('price', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('cost', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('wholesale', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('picture', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('category_id', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy('name')
                            ->paginate((int)$request->perpage);

        $pages = Pages::generate($products);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'pages' => $pages,
                'data' => $products->all()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $products = new Products;
        $products->name = $request->name;
        $products->description = $request->description;
        $products->price = $request->price;
        $products->cost = $request->cost;
        $products->wholesale = $request->wholesale;
        $products->picture = $request->picture;
        $products->category_id = $request->category_id;
        $products->save();

        return response()->json([
            'type' => 'success',
            'message' => 'products saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $products = Products::find($id);
        return response()->json([
            'type' => 'success',
            'data' => $products
        ], 200);
    }

    public function update($id, Request $request)
    {
        $products = Products::find($id);
        $products->name = $request->name;
        $products->description = $request->description;
        $products->price = $request->price;
        $products->cost = $request->cost;
        $products->wholesale = $request->wholesale;
        $products->picture = $request->picture;
        $products->category_id = $request->category_id;
        $products->save();

        return response()->json([
            'type' => 'success',
            'message' => 'products updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $products = Products::find($id);
        $products->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'products deleted successfully'
        ], 201);

    }
}
