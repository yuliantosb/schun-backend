<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Permission;
use App\Helpers\Pages;
use App\Helpers\Common;

class PermissionController extends Controller
{

    public function index(Request $request)
    {
        $permissions = Permission::with(['parent'])->where(function($where) use ($request){
    
                    if (!empty($request->keyword)) {
                        $where->whereHas('parent', function($wherehas) use ($request){
                            $wherehas->where('name', 'like', '%'.$request->keyword.'%');
                        })
                        ->orWhere('name', 'like', '%'.$request->keyword.'%')
                        ->orWhere('slug', 'like', '%'.$request->keyword.'%')
                        ->orWhere('description', 'like', '%'.$request->keyword.'%');
                    }
                })
    
                ->orderBy('name')
                ->paginate((int)$request->perpage);
    
        $pages = Pages::generate($permissions);
    
        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
            'total' => $permissions->total(),
            'per_page' => $permissions->perPage(),
            'current_page' => $permissions->currentPage(),
            'last_page' => $permissions->lastPage(),
            'from' => $permissions->firstItem(),
            'to' => $permissions->lastItem(),
            'pages' => $pages,
            'data' => $permissions->all()
            ]
        ]);
    }

    public function getParent()
    {
        $no_parent = [['_id' => '0', 'name' => 'No parent']];

        $parents = Permission::select('_id', 'name')->doesntHave('parent')->get();

        return response()->json([
            'type' => 'success',
            'data' => collect($no_parent)->merge($parents),
        ]);
    }

    public function store(Request $request)
    {
        $permission = new Permission;
        $permission->name = $request->name;
        $permission->slug = Common::createSlug($request->name, 'permission');
        $permission->description = $request->description;
        $permission->parent_id = !empty($request->parent_id) ? $request->parent_id : 0;
        $permission->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Permission saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $permission = Permission::find($id);
        return response()->json([
            'type' => 'success',
            'data' => $permission
        ], 200);
    }

    public function update($id, Request $request)
    {
        $permission = Permission::find($id);
        $permission->name = $request->name;
        $permission->slug = Common::createSlug($request->name, 'permission', $id);
        $permission->description = $request->description;
        $permission->parent_id = !empty($request->parent_id) ? $request->parent_id : 0;
        $permission->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Permission updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);
        $permission->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'Permission deleted successfully'
        ], 201);

    }
}
