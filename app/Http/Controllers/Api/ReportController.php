<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Sales;
use App\Purchase;
use App\Expense;
use App\StockDetail;
use PDF;

class ReportController extends Controller
{
    public function sales(Request $request)
    {

        $data['request'] = $request;
        $data['sales'] = Sales::with(['customer', 'details'])->where(function($where) use ($request){           
                    if (!empty($request->start_date) && !empty($request->end_date)) {
                        $where->where('created_at', '>=', Carbon::parse($request->start_date))
                            ->where('created_at', '<=', Carbon::parse($request->end_date)->addDay());
                    }
                })
                ->orderBy('created_at')
                ->get();
        
        $pdf = PDF::loadView('pdf.sales', $data);
        return $pdf->download('sales_from'.$request->start_sate.'_to_'.$request->end_date.'.pdf');
    }

    public function purchase(Request $request)
    {
        $data['request'] = $request;
        $data['purchase'] = Purchase::with(['user', 'supplier'])->where(function($where) use ($request){           
                    if (!empty($request->start_date) && !empty($request->end_date)) {
                        $where->where('created_at', '>=', Carbon::parse($request->start_date))
                            ->where('created_at', '<=', Carbon::parse($request->end_date)->addDay());
                    }
                })
                ->orderBy('created_at')
                ->get();
        
        $pdf = PDF::loadView('pdf.purchase', $data);
        return $pdf->download('purchase_from'.$request->start_sate.'_to_'.$request->end_date.'.pdf');
    }

    public function expense(Request $request)
    {
        $data['request'] = $request;
        $data['expenses'] = Expense::with(['user'])->where(function($where) use ($request){           
                    if (!empty($request->start_date) && !empty($request->end_date)) {
                        $where->where('created_at', '>=', Carbon::parse($request->start_date))
                            ->where('created_at', '<=', Carbon::parse($request->end_date)->addDay());
                    }
                })
                ->orderBy('created_at')
                ->get();
        
        $pdf = PDF::loadView('pdf.expense', $data);
        return $pdf->download('expense_from'.$request->start_sate.'_to_'.$request->end_date.'.pdf');
    }

    public function stock(Request $request)
    {
        $data['request'] = $request;
        $data['stock'] = StockDetail::with(['stock', 'stock.product'])
                            ->where(function($where) use ($request){
                                if (!empty($request->start_date) && !empty($request->end_date)) {
                                    $where->where('created_at', '>=', Carbon::parse($request->start_date))
                                           ->where('created_at', '<=', Carbon::parse($request->end_date)->addDay());
                                }
                            })
                            ->whereHas('stock.product', function($whereHas) use ($request){
                                if (!empty($request->keyword)){
                                    $whereHas->where('name', 'like', '%'.$request->keyword.'%');
                                }

                            })
                            ->orderBy('created_at')
                            ->get();
        
        $pdf = PDF::loadView('pdf.stock', $data);
        return $pdf->download('stock_from'.$request->start_sate.'_to_'.$request->end_date.'.pdf');

    }
}
