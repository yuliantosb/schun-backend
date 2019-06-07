<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Grouppacketdetails;
use App\Helpers\Pages;

class GrouppacketdetailsController extends Controller
{
    public function index(Request $request)
    {
        $grouppacketdetails = Grouppacketdetails::where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('packet_id', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('product_id', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('service_id', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy('packet_id')
                            ->paginate((int)$request->perpage);

        $pages = Pages::generate($grouppacketdetails);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $grouppacketdetails->total(),
                'per_page' => $grouppacketdetails->perPage(),
                'current_page' => $grouppacketdetails->currentPage(),
                'last_page' => $grouppacketdetails->lastPage(),
                'from' => $grouppacketdetails->firstItem(),
                'to' => $grouppacketdetails->lastItem(),
                'pages' => $pages,
                'data' => $grouppacketdetails->all()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $grouppacketdetails = new Grouppacketdetails;
        $grouppacketdetails->packet_id = $request->packet_id;
        $grouppacketdetails->product_id = $request->product_id;
        $grouppacketdetails->service_id = $request->service_id;
        $grouppacketdetails->save();

        return response()->json([
            'type' => 'success',
            'message' => 'grouppacketdetails saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $grouppacketdetails = Grouppacketdetails::find($id);
        return response()->json([
            'type' => 'success',
            'data' => $grouppacketdetails
        ], 200);
    }

    public function update($id, Request $request)
    {
        $grouppacketdetails = Grouppacketdetails::find($id);
        $grouppacketdetails->packet_id = $request->packet_id;
        $grouppacketdetails->product_id = $request->product_id;
        $grouppacketdetails->service_id = $request->service_id;
        $grouppacketdetails->save();

        return response()->json([
            'type' => 'success',
            'message' => 'grouppacketdetails updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $grouppacketdetails = Grouppacketdetails::find($id);
        $grouppacketdetails->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'grouppacketdetails deleted successfully'
        ], 201);

    }
}
