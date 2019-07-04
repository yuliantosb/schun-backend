<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Sales;
use App\Helpers\Pages;
use App\Customer;
use App\SalesDetail;
use App\Stock;
use App\StockDetail;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $ordering = json_decode($request->ordering);
        $sales = Sales::with(['customer'])->where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('description', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('price', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy($ordering->type, $ordering->sort)
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
        $customer_name = !empty($request->customer_id) ? Customer::find($request->customer_id)->name : null;
        $subtotal = collect($request->details)->sum('subtotal');
        $sales = new Sales;
        $sales->customer_name = $customer_name;
        $sales->subtotal = $subtotal;
        $sales->tax = $request->tax;
        $sales->discount = $request->discount;
        $sales->customer_id = $request->customer_id;
        $sales->total = $subtotal + $request->tax - $request->discount;
        $sales->payment_type = $request->payment_type;
        $sales->amount = (float)str_replace(',','',$request->amount);
        $sales->card_number = $request->card_number;
        $sales->card_expired = $request->card_expired;
        $sales->changes = $request->changes;
        $sales->is_hold = $request->is_hold;
        $sales->save();


        foreach ($request->details as $detail) {

            $sales_details = new SalesDetail;
            $sales_details->product_id = $detail['id'];
            $sales_details->price = $detail['price'];
            $sales_details->qty = $detail['qty'];
            $sales_details->subtotal = $detail['subtotal'];
            $sales->details()->save($sales_details);

            if (!$request->is_hold) {

                $stock = Stock::where('products_id', $detail['id'])->first();
                $stock->decrement('amount', 1);
                $stock->product()->update(['stock' => $stock->amount]);
    
                $stock_details = new StockDetail;
                $stock_details->amount = (int) $detail['qty'];
                $stock_details->description = 'Sales #'.$sales->id;
                $stock_details->sales_id = $sales->id;
                $stock_details->type = 'deduction';
                $stock->details()->save($stock_details);
            }

        }

        return response()->json([
            'type' => 'success',
            'message' => 'Sales saved successfully!'
        ]);

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

    public function customer(Request $request)
    {
        
        $customers = Customer::where('name', 'like', '%'.$request->name.'%')
                ->get();
        
        return response()->json([
            'type' => 'success',
            'data' => $customers
        ], 200);
    }
}
