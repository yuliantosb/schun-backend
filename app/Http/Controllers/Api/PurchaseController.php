<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Pages;
use App\Purchase;
use App\PurchaseDetail;
use App\Stock;
use Carbon\Carbon;
use App\StockDetail;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $ordering = json_decode($request->ordering);
        $purchases = Purchase::with(['user', 'supplier'])
                            ->where(function($where) use ($request){
                                
                                if (!empty($request->start_date) && !empty($request->end_date)) {
                                    $where->where('created_at', '>=', Carbon::parse($request->start_date))
                                           ->where('created_at', '<=', Carbon::parse($request->end_date)->addDay());
                                }

                                if (!empty($request->keyword)) {
                                    $where->where('reference', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('subtotal', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('tax', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('discount', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('total', 'like', '%'.$request->keyword.'%');
                                }
                            })
                    ->orderBy($ordering->type, $ordering->sort)
                    ->paginate((int)$request->perpage);

        $pages = Pages::generate($purchases);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $purchases->total(),
                'per_page' => $purchases->perPage(),
                'current_page' => $purchases->currentPage(),
                'last_page' => $purchases->lastPage(),
                'from' => $purchases->firstItem(),
                'to' => $purchases->lastItem(),
                'pages' => $pages,
                'data' => $purchases->all()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'reference' => 'required|unique:purchases,reference',
            'user_id' => 'required',
            'supplier_id' => 'required'
        ]);

        $subtotal = collect($request->details)->sum('subtotal');

        $purchase = new Purchase;
        $purchase->subtotal = $subtotal;
        $purchase->discount = $request->discount;

        if (!empty($request->file)) {
            $purchase->evidence = $request->evidence;
            $purchase->file = $request->file;
        }

        $purchase->notes = $request->notes;
        $purchase->percent = $request->percent;
        $purchase->reference = $request->reference;
        $purchase->supplier_id = $request->supplier_id;
        $purchase->supplier_name = $request->supplier_name;
        $purchase->tax = $request->tax;
        $purchase->user_id = $request->user_id;
        $purchase->user_name = $request->user_name;
        $purchase->total = $subtotal + $request->tax - $request->discount;
        $purchase->save();

        foreach ($request->details as $detail) {

            $purchase_detail = new PurchaseDetail;
            $purchase_detail->product_name = $detail['name'];
            $purchase_detail->product_id = $detail['id'];
            $purchase_detail->price = (float) $detail['price'];
            $purchase_detail->qty = (int) $detail['qty'];
            $purchase_detail->subtotal = (float) $detail['subtotal'];
            $purchase->details()->save($purchase_detail);

            $stock = Stock::where('products_id', $detail['id'])->first();
            $stock->increment('amount', (int) $detail['qty']);
            $stock->product()->update(['stock' => $stock->amount]);

            $stock_details = new StockDetail;
            $stock_details->amount = (int) $detail['qty'];
            $stock_details->description = 'Purchase #'.$purchase->reference;
            $stock_details->purchase_id = $purchase->id;
            $stock_details->type = 'induction';
            $stock->details()->save($stock_details);
        
        }

        return response()->json([
            'type' => 'success',
            'message' => 'Sales saved successfully!',
            'data' => $purchase
        ]);
    }

    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'user', 'details'])->find($id);

        $carts = [];
        foreach ($purchase->details as $cart) {
            $carts[$cart->product_id] = [
                'id' => $cart->product_id,
                'name' => $cart->product_name,
                'price' => $cart->price,
                'qty' => $cart->qty,
                'subtotal' => $cart->subtotal
            ];
        }

        return response()->json([
            'type' => 'success',
            'data' => $purchase,
            'carts' => $carts

        ], 200);
    }

    public function update($id, Request $request)
    {

        $request->validate([
            'reference' => 'required|unique:purchases,reference,'.$id.',_id',
            'user_id' => 'required',
            'supplier_id' => 'required'
        ]);

        $subtotal = collect($request->details)->sum('subtotal');

        $purchase = Purchase::with('details')->find($id);
        $purchase->subtotal = $subtotal;
        $purchase->discount = $request->discount;

        if (!empty($request->file)) {
            $purchase->evidence = $request->evidence;
            $purchase->file = $request->file;
        }

        $purchase->notes = $request->notes;
        $purchase->percent = $request->percent;
        $purchase->reference = $request->reference;
        $purchase->supplier_id = $request->supplier_id;
        $purchase->supplier_name = $request->supplier_name;
        $purchase->tax = $request->tax;
        $purchase->user_id = $request->user_id;
        $purchase->user_name = $request->user_name;
        $purchase->total = $subtotal + $request->tax - $request->discount;
        $purchase->save();

        foreach ($purchase->details as $detail) {

            $stock = Stock::where('products_id', $detail->product_id)->first();
            $stock->decrement('amount', (int) $detail->qty);
            $stock->product()->update(['stock' => $stock->amount]);

            $stock_details = StockDetail::where('stock_id', $stock->id)
                                    ->where('purchase_id', $purchase->id)
                                    ->delete();
        }

        $purchase->details()->delete();

        foreach ($request->details as $detail) {

            $purchase_detail = new PurchaseDetail;
            $purchase_detail->product_name = $detail['name'];
            $purchase_detail->product_id = $detail['id'];
            $purchase_detail->price = (float) $detail['price'];
            $purchase_detail->qty = (int) $detail['qty'];
            $purchase_detail->subtotal = (float) $detail['subtotal'];
            $purchase->details()->save($purchase_detail);

            $stock = Stock::where('products_id', $detail['id'])->first();
            $stock->increment('amount', (int) $detail['qty']);
            $stock->product()->update(['stock' => $stock->amount]);

            $stock_details = new StockDetail;
            $stock_details->amount = (int) $detail['qty'];
            $stock_details->description = 'Purchase #'.$purchase->reference;
            $stock_details->purchase_id = $purchase->id;
            $stock_details->type = 'induction';
            $stock->details()->save($stock_details);
        
        }

        return response()->json([
            'type' => 'success',
            'message' => 'Sales saved successfully!',
            'data' => $purchase
        ]);
    }

    public function destroy($id)
    {
        $purchase = Purchase::find($id);

        foreach ($purchase->details as $detail) {

            $stock = Stock::where('products_id', $detail->product_id)->first();
            $stock->decrement('amount', (int) $detail->qty);
            $stock->product()->update(['stock' => $stock->amount]);

            $stock_details = StockDetail::where('stock_id', $stock->id)
                                    ->where('purchase_id', $purchase->id)
                                    ->delete();
        }

        $purchase->details()->delete();
        $purchase->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'Sales deleted successfully!',
        ]);
    }
}
