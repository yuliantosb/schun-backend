<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\Helpers\Pages;
use App\Helpers\Common;
use App\Permission;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('slug', 'like', '%'.$request->keyword.'%')
                                        ->orWhere('description', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy('name')
                            ->paginate((int)$request->perpage);

        $pages = Pages::generate($roles);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $roles->total(),
                'per_page' => $roles->perPage(),
                'current_page' => $roles->currentPage(),
                'last_page' => $roles->lastPage(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastItem(),
                'pages' => $pages,
                'data' => $roles->all()
            ]
        ]);
    }

    public function getPermissions()
    {
        $permissions = Permission::with('children')->doesntHave('parent')->get();
        return response()->json([
            'type' => 'success',
            'data' => $permissions
        ], 200);
    }

    public function store(Request $request)
    {
        $role = new Role;
        $role->name = $request->name;
        $role->slug = Common::createSlug($request->name, 'role');
        $role->description = $request->description;
        $role->save();

        $permission = new Permission;
        foreach ($request->permissions as $perm => $value) {
            $permission->{$perm} = $value;
        }
        $role->permissions()->save($permission);


        return response()->json([
            'type' => 'success',
            'message' => 'Role saved successfully'
        ], 201);
    }
}
