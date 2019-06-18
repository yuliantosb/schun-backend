<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Helpers\Pages;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $ordering = json_decode($request->ordering);
        $category = Category::with('parent')->where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhereHas('parent', function($whereHas) use ($request) {
                                            $whereHas->where('name', 'like', '%'.$request->keyword.'%');
                                        })
                                        ->orWhere('description', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy($ordering->type, $ordering->sort)
                            ->paginate((int)$request->perpage);

        $pages = Pages::generate($category);

        return response()->json([
            'type' => 'success',
            'message' => 'fetch data stock in success!',
            'data' => [
                'total' => $category->total(),
                'per_page' => $category->perPage(),
                'current_page' => $category->currentPage(),
                'last_page' => $category->lastPage(),
                'from' => $category->firstItem(),
                'to' => $category->lastItem(),
                'pages' => $pages,
                'data' => $category->all()
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        if (!empty($request->parent_id)) {
            $parent = Category::find($request->parent_id);
        }

        $category = new Category;
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->parent_name = !empty($request->parent_id) ? $parent->name : null;
        $category->description = $request->description;
        $category->save();

        return response()->json([
            'type' => 'success',
            'message' => 'category saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $category = Category::with('parent')->find($id);
        return response()->json([
            'type' => 'success',
            'data' => $category
        ], 200);
    }

    public function update($id, Request $request)
    {

        $request->validate([
            'name' => 'required'
        ]);

        if (!empty($request->parent_id)) {
            $parent = Category::find($request->parent_id);
        }

        $category = Category::find($id);
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->parent_name = !empty($request->parent_id) ? $parent->name : null;
        $category->description = $request->description;
        $category->save();

        return response()->json([
            'type' => 'success',
            'message' => 'Category updated successfully'
        ], 201);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->deleted_by = auth()->user()->id;
        $category->save();
        $category->delete();

        return response()->json([
            'type' => 'success',
            'message' => 'category deleted successfully'
        ], 201);

    }

    public function parent(Request $request)
    {
        $categories = Category::doesntHave('parent')
                        ->where(function($where) use ($request){
                            if (!empty($request->id)) {
                                $where->where('_id', '!=', $request->id);
                            }
                        })
                        ->where('name', 'like', '%'.$request->name.'%')
                        ->get();
        
        return response()->json([
            'type' => 'success',
            'data' => $categories
        ], 200);
        
    }
}
