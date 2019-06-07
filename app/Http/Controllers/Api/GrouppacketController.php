<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Grouppacket;
use App\Helpers\Pages;

class GrouppacketController extends Controller
{
    public function index(Request $request)
    {
        $grouppacket = Grouppacket::where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('description', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy('name')
                            ->paginate((int)$request->perpage);

        $pages = Pages::generate($grouppacket);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $grouppacket->total(),
                'per_page' => $grouppacket->perPage(),
                'current_page' => $grouppacket->currentPage(),
                'last_page' => $grouppacket->lastPage(),
                'from' => $grouppacket->firstItem(),
                'to' => $grouppacket->lastItem(),
                'pages' => $pages,
                'data' => $grouppacket->all()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $grouppacket = new Grouppacket;
        $grouppacket->name = $request->name;
        $grouppacket->description = $request->description;
        $grouppacket->save();

        return response()->json([
            'type' => 'success',
            'message' => 'grouppacket saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $grouppacket = Grouppacket::find($id);
        return response()->json([
            'type' => 'success',
            'data' => $grouppacket
        ], 200);
    }

    public function update($id, Request $request)
    {
        $grouppacket = Grouppacket::find($id);
        $grouppacket->name = $request->name;
        $grouppacket->description = $request->description;
        $grouppacket->save();

        return response()->json([
            'type' => 'success',
            'message' => 'grouppacket updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $grouppacket = Grouppacket::find($id);
        $grouppacket->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'grouppacket deleted successfully'
        ], 201);

    }
}
