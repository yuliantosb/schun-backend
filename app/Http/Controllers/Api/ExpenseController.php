<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Expense;
use App\Helpers\Pages;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expense = Expense::where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('reference', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('amount', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('note', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('incharge', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('evidence', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy('reference')
                            ->paginate((int)$request->perpage);

        $pages = Pages::generate($expense);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $expense->total(),
                'per_page' => $expense->perPage(),
                'current_page' => $expense->currentPage(),
                'last_page' => $expense->lastPage(),
                'from' => $expense->firstItem(),
                'to' => $expense->lastItem(),
                'pages' => $pages,
                'data' => $expense->all()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $expense = new Expense;
        $expense->reference = $request->reference;
        $expense->amount = $request->amount;
        $expense->note = $request->note;
        $expense->incharge = $request->incharge;
        $expense->evidence = $request->evidence;
        $expense->save();

        return response()->json([
            'type' => 'success',
            'message' => 'expense saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $expense = Expense::find($id);
        return response()->json([
            'type' => 'success',
            'data' => $expense
        ], 200);
    }

    public function update($id, Request $request)
    {
        $expense = Expense::find($id);
        $expense->reference = $request->reference;
        $expense->amount = $request->amount;
        $expense->note = $request->note;
        $expense->incharge = $request->incharge;
        $expense->evidence = $request->evidence;
        $expense->save();

        return response()->json([
            'type' => 'success',
            'message' => 'expense updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);
        $expense->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'expense deleted successfully'
        ], 201);

    }
}
