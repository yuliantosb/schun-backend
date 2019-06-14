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
        $category = Category::with('parent')->where(function($where) use ($request){

                                if (!empty($request->keyword)) {
                                    $where->where('name', 'like', '%'.$request->keyword.'%')
                                        ->orWhereHas('parent', function($whereHas) use ($request) {
                                            $whereHas->where('name', 'like', '%'.$request->keyword.'%');
                                        })
                                        ->orWhere('description', 'like', '%'.$request->keyword.'%');
                                }
                            })
                            ->orderBy('name')
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
        $category = new Category;
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
        $category->description = $request->description;
        $category->save();

        return response()->json([
            'type' => 'success',
            'message' => 'category saved successfully'
        ], 201);
    }

    public function show($id)
    {
        $category = Category::find($id);
        return response()->json([
            'type' => 'success',
            'data' => $category
        ], 200);
    }

    public function update($id, Request $request)
    {
        $category = Category::find($id);
        $category->name = $request->name;
        $category->parent_id = $request->parent_id;
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
