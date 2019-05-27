<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\StockIn;
use App\Item;
use Carbon\Carbon;
use App\Helpers\Pages;

class StockinController extends Controller
{
    // Function Untuk Menampilkan Data StockIn
    public function index(Request $request)
    {

        $stockins = Stockin::with(['items', 'items.uom'])
                            ->where(function($where) use ($request){
                                if (!empty($request->start_date) && !empty($request->end_date)) {
                                    $where->where('stock_in_date', '>=', Carbon::parse($request->start_date))
                                            ->where('stock_in_date', '<=', Carbon::parse($request->end_date));
                                }

                                if (!empty($request->keyword)) {
                                    $where->whereHas('items', function($whereHas) use ($request){
                                        $whereHas->where('name', 'like', '%'.$request->keyword.'%');
                                    })
                                    ->orWhereHas('items.uom', function($whereHas) use ($request){
                                        $whereHas->where('name', 'like', '%'.$request->keyword.'%');
                                    })
                                    ->orWhere('evidence', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy('items.name')
                            ->paginate((int)$request->perpage);

        $pages = Pages::generate($stockins);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $stockins->total(),
                'per_page' => $stockins->perPage(),
                'current_page' => $stockins->currentPage(),
                'last_page' => $stockins->lastPage(),
                'from' => $stockins->firstItem(),
                'to' => $stockins->lastItem(),
                'pages' => $pages,
                'data' => $stockins->all()
            ]
        ]);
    }

    // Function Untuk Menginput Data Stock In
    public function store(Request $request)
    {
        if ($request->has('file')) {

            if (preg_match('/^data:image\/(\w+);base64,/', $request->file)) {
                
                $img = substr($request->file, strpos($request->file, ',') + 1);
                $img = base64_decode($img);
                Storage::disk('local')->put($request->evidence, $img);
            }
        }

        $stockin = new StockIn;
        $stockin->item_id = $request->item_id;
        $stockin->stock_in_date = Carbon::parse($request->stock_in_date);
        $stockin->price = str_replace(',', '', $request->price);
        $stockin->qty = str_replace(',', '', $request->qty);
        $stockin->evidence = $request->evidence;
        $stockin->save();

        return response()->json([
            'type' => 'success',
            'message' => 'fetch input data stock in success!',
            'data' => $request->all()
        ]);
    }

    // Function Untuk Mengambil Id Data Stock In
    public function show($id)
    {
        $stockins = Stockin::find($id);

        if(! $stockins) {
            return response()->json([
                'message' => 'stockin not found'
            ]);
        }

        return $stockins;
    }

    // Function Untuk Mengupdate Data Stock In
    public function update(Request $request, $id)
    {
        $stockins = Stockin::find($id);

        if($stockins) {
            $stockins->update($request->all());

            return response()->json([
                'message' => 'fetch update data stock in success!'
            ]);
        }

        return response()->json([
            'message' => 'stock in not found'
        ], 404);
    }

    // Function Untuk Mengdelete Data Stock In
    public function delete($id)
    {
        $stockins = Stockin::find($id);

        if($stockins) {
            $stockins->delete();

            return response()->json([
                'message' => 'fetch delete data stock in success!'
            ]);
        }

        return response()->json([
            'message' => 'stock in not found!'
        ], 404);
    }

    public function items()
    {
        $items = Item::get();
        return response()->json([
            'type' => 'success',
            'message' => 'fetch input data stock in success!',
            'data' => $items
        ]);
    }
}
