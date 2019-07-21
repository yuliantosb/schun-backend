<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Products;
use App\Helpers\Pages;
use App\Stock;
use App\StockDetail;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        $ordering = json_decode($request->ordering);
        $products = Products::with(['stock'])
                            ->where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('stock', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy($ordering->type, $ordering->sort)
                            ->paginate((int)$request->perpage);

        $products->map(function($product) {
            $product->total_sales = $product->stock->details->where('sales_id', '!=', null)->where('created_at', '>=', Carbon::now()->subDay())->sum('amount');
            $product->total_purchase = $product->stock->details->where('purchase_id', '!=', null)->where('created_at', '>=', Carbon::now()->subDay())->sum('amount');
        });

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

    public function update($id, Request $request)
    {
        $request->validate([
            'stock' => 'required|numeric',
            'notes' => 'required|string'
        ]);

        $stock = Stock::where('products_id', $id)->first();
        $stock->increment('amount', (int) $request->stock);
        $stock->product()->update(['stock' => $stock->amount]);

        $stock_details = new StockDetail;
        $stock_details->amount = (int) str_replace('-', '', $request->stock);
        $stock_details->description = $request->notes.' By: '.auth()->user()->name;
        $stock_details->type = $request->stock > 0 ? 'induction' : 'deduction';
        $stock->details()->save($stock_details);

        return response()->json([
            'type' => 'success',
            'message' => 'Stock adjusted',
            'request' => $request->all()
        ]);

    }

    public function show($id)
    {
        $stock = Stock::with(['details'])->where('products_id', $id)->first();

        return response()->json([
            'type' => 'success',
            'data' => $stock
        ], 200);
    }
}
