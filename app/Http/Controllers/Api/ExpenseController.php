<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Expense;
use App\Helpers\Pages;
use App\User;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $expense = Expense::with('user')->where(function($where) use ($request){

                if (!empty($request->start_date) && !empty($request->end_date)) {
                    $where->where('created_at', '>=', Carbon::parse($request->start_date))
                           ->where('created_at', '<=', Carbon::parse($request->end_date)->addDay());
                }

                if (!empty($request->keyword)) {
                    $where->orWhere('reference', 'like', '%'.$request->keyword.'%')
                        ->orWhere('amount', 'like', '%'.$request->keyword.'%')
                        ->orWhere('notes', 'like', '%'.$request->keyword.'%')
                        ->orWhereHas('user', function($whereHas) use ($request) {
                            $whereHas->where('name', 'like', '%'.$request->keyword.'%');
                        })
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

        $request->validate([
            'reference' => 'required',
            'user_id' => 'required'
        ]);

        $expense = new Expense;
        $expense->reference = $request->reference;
        $expense->amount = str_replace(',', '', $request->amount);
        $expense->notes = $request->notes;
        $expense->user_id = $request->user_id;

        if ($request->has('file')) {

            if ($request->has('file')) {

                $expense->file = $request->file;
                $expense->evidence = $request->evidence;
            }
        }

        $expense->save();

        return response()->json([
            'type' => 'success',
            'message' => 'expense saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $expense = Expense::with('user')->find($id);
        return response()->json([
            'type' => 'success',
            'data' => $expense
        ], 200);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'reference' => 'required',
            'user_id' => 'required'
        ]);

        $expense = Expense::find($id);
        $expense->reference = $request->reference;
        $expense->amount = str_replace(',', '', $request->amount);
        $expense->notes = $request->notes;
        $expense->user_id = $request->user_id;
        
        if ($request->has('file')) {

            $expense->file = $request->file;
            $expense->evidence = $request->evidence;
        }

        $expense->save();

        return response()->json([
            'type' => 'success',
            'message' => 'expense updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);

        if (!empty($expense->evidence)) {
            if (Storage::disk('public')->exists($expense->evidence)) {
                Storage::disk('public')->delete($expense->evidence);
            }
        }

        $expense->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'expense deleted successfully'
        ], 201);

    }

    public function user(Request $request)
    {
        
        $users = User::where('name', 'like', '%'.$request->name.'%')
                ->get();
        
        return response()->json([
            'type' => 'success',
            'data' => $users
        ], 200);
    }
}
