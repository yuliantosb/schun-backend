<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Products;
use App\Category;
use App\Helpers\Pages;
use App\Stock;
use App\StockDetail;
use DB;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $ordering = json_decode($request->ordering);
        $products = Products::with(['category', 'stock'])
                            ->where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('code', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('description', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('price', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('cost', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('wholesale', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('picture', 'like', '%'.$request->keyword.'%')
                                        ->orWhereHas('category', function($whereHas) use ($request){
                                            $whereHas->where('name', 'like', $request->keyword);
                                        });
                                }
                            })
                            ->orderBy($ordering->type, $ordering->sort)
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
        $request->validate([
            'name' => 'required',
            'code' => 'unique:products,code',
            'price' => 'required',
            'wholesale' => 'required',
            'cost' => 'required',
            'category_id' => 'required'
        ]);

        $category = Category::find($request->category_id);
        $product = new Products;
        $product->code = $request->code;
        $product->name = $request->name;
        $product->price = str_replace(',', '', $request->price);
        $product->wholesale = str_replace(',', '', $request->wholesale);
        $product->cost = str_replace(',', '', $request->cost);
        $product->stock = str_replace(',', '', $request->stock);

        if (!empty($request->picture)) {
            $product->picture = $request->picture;
            $product->file = $request->file;
        }

        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->category = $category->name;
        $product->save();

        $stock = new Stock;
        $stock->amount = str_replace(',', '', $request->stock);
        $product->stock()->save($stock);

        $stock_detail = new StockDetail;
        $stock_detail->amount = str_replace(',', '', $request->stock);
        $stock_detail->description = 'Init new product';
        $stock->details()->save($stock_detail);

        return response()->json([
            'type' => 'success',
            'message' => 'product saved successfully'
        ], 201);

    }

    public function show($id)
    {
        $products = Products::with(['category', 'stock'])->find($id);
        return response()->json([
            'type' => 'success',
            'data' => $products
        ], 200);
    }

    public function update($id, Request $request)
    {
        $category = Category::find($request->category_id);
        $product = Products::find($id);
        $product->code = $request->code;
        $product->name = $request->name;
        $product->price = str_replace(',', '', $request->price);
        $product->wholesale = str_replace(',', '', $request->wholesale);
        $product->cost = str_replace(',', '', $request->cost);

        if (!empty($request->picture)) {
            $product->picture = $request->picture;
            $product->file = $request->file;
        }

        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->category = $category->name;
        $product->save();

        return response()->json([
            'type' => 'success',
            'message' => 'products updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $products = Products::find($id);
        $products->deleted_by = auth()->user()->id;
        $products->save();
        $products->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'products deleted successfully'
        ], 201);

    }

    public function category(Request $request)
    {
        
        $categories = Category::where('name', 'like', '%'.$request->name.'%')
                ->get();
        
        return response()->json([
            'type' => 'success',
            'data' => $categories
        ], 200);
    }
}
