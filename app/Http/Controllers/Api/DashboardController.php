<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Sales;
use App\Purchase;
use Carbon\Carbon;
use App\Setting;
use App\Expense;

class DashboardController extends Controller
{
    public function daily()
    {        
        $data = $this->small();
        $sales = $this->bar();

        return response()->json([
            'type' => 'success',
            'data' => $data,
            'sales' => $sales
        ], 200);       
        
    }

    protected function small()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        $sales = Sales::where('created_at', '>=', Carbon::now()->subDays(7))
                            ->where('created_at', '<=', Carbon::now()->addDay())
                            ->get()
                            ->groupBy(function ($val) {
                                return Carbon::parse($val->created_at)->format('d');
                            });

                            
        $purchases = Purchase::where('created_at', '>=', Carbon::now()->subDays(7))
                    ->where('created_at', '<=', Carbon::now()->addDay())
                    ->get()
                    ->groupBy(function ($val) {
                        return Carbon::parse($val->created_at)->format('d');
                    });
                    
        $expenses = Expense::where('created_at', '>=', Carbon::now()->subDays(7))
                    ->where('created_at', '<=', Carbon::now()->addDay())
                    ->get()
                    ->groupBy(function ($val) {
                        return Carbon::parse($val->created_at)->format('d');
                    });
                    
        $sales_value = Sales::where('created_at', '>', Carbon::parse(date('Y-m-d')))->get();
        $purchase_value = Purchase::where('created_at', '>', Carbon::parse(date('Y-m-d')))->get();
        $expense_value = Expense::where('created_at', '>', Carbon::parse(date('Y-m-d')))->get();

        
        $sales_value_total = $sales_value->sum('total');
        $purchase_value_total = $purchase_value->sum('total');
        $expense_value_total = $expense_value->sum('amount');
        $expense_purchase_value = $purchase_value_total + $expense_value_total;
        $net_income_value = $sales_value_total - $expense_purchase_value;


        $result_sales = $sales->map(function($sale){
            return $sale->sum('total');
        });

        $result_purchase = $purchases->map(function($purchase){
            return $purchase->sum('total');
        });

        $result_expense = $expenses->map(function($expense){
            return $expense->sum('amount');
        });

        $merge_expense_purchase = $result_expense->union($result_purchase);
        $result_expense_purchase = $result_purchase;

        foreach($merge_expense_purchase as $k => $v) {
            if($result_expense_purchase->has($k)) {
                $result_expense_purchase[$k] += $v;
            } else {
                $result_expense_purchase[$k] = $v; 
            }
        }

        $result_sales_flatten = $result_sales->flatten()->toArray();
        
        $merge_net = $result_expense_purchase->union($result_sales);
        $result_net = $result_sales;
        
        foreach($merge_net as $k => $v) {
            if($result_net->has($k)) {
                $result_net[$k] -= $v;
            } else {
                $result_net[$k] = $v; 
            }
        }
        
        $result_expense_purchase = $result_expense_purchase->flatten()->toArray();
        $result_net = $result_net->flatten()->toArray();

        $sales_subs = !empty($result_sales[count($result_sales) - 2]) ? $result_sales[count($result_sales) - 2] : 0;
        $expense_purchase_subs = !empty($result_expense_purchase[count($result_expense_purchase) - 2]) ? $result_expense_purchase[count($result_expense_purchase) - 2] : 0;
        $net_sub = !empty($result_net[count($result_net) - 2]) ? $result_net[count($result_net) - 2] : 0;


        $data['sales'] = [
            'data' => $result_sales_flatten,
            'value' => $currency.number_format($sales_value_total,2,$decimal_separator,$thousand_separator),
            'rank' => $sales_subs > $sales_value_total ? 'decrease' : 'increase',
            'percentage' => $sales_subs > $sales_value_total ? $sales_subs > 0 ? round(($sales_subs / $sales_value_total) * 100, 2) : 0 : $sales_subs > 0 ? round(($sales_value_total / $sales_subs) * 100, 2) : 0 
        ];

        $data['expense_purchase'] = [
            'data' => $result_expense_purchase,
            'value' => $currency.number_format($expense_purchase_value,2,$decimal_separator,$thousand_separator),
            'rank' => $expense_purchase_subs > $expense_purchase_value ? 'decrease' : 'increase',
            'percentage' => $expense_purchase_subs > $expense_purchase_value ? $expense_purchase_subs > 0 ? round(($expense_purchase_subs / $expense_purchase_value) * 100, 2) : 0 : $expense_purchase_subs > 0 ? round(($expense_purchase_value / $expense_purchase_subs) * 100, 2) : 0 
        ];

        $data['net'] = [
            'data' => $result_net,
            'value' => $currency.number_format($net_income_value,2,$decimal_separator,$thousand_separator),
            'rank' => $net_sub > $net_income_value ? 'decrease' : 'increase',
            'percentage' => $net_sub > $net_income_value ? $net_sub > 0 ? round(($net_sub / $net_income_value) * 100, 2) : 0 : $net_sub > 0 ? round(($net_income_value / $net_sub) * 100, 2) : 0 
        ];


        return $data;
    }

    protected function bar()
    {
        $setting = Setting::getSetting();
        $currency = !empty($setting->currency) ?  $setting->currency : 'Rp';
        $decimal_separator = !empty($setting->decimal_separator) ? $setting->decimal_separator : '.';
        $thousand_separator = !empty($setting->thousand_separator) ? $setting->thousand_separator : ',';

        $sales = Sales::where('created_at', '>', Carbon::parse(date('Y-m-d')))
                    ->get()
                    ->groupBy(function ($val) {
                return Carbon::parse($val->created_at)->format('H');
            });

        $result_sales = $sales->map(function($sale){
            return $sale->sum('total');
        });

        return [
                    'data' => $result_sales->flatten(),
                    'key' => $result_sales->keys()
        ];
    }
}
