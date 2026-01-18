<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{
     public function index(Request $request)
    {
        $admin = $request->user(); 
        $categories = Category::where('admin_id', $admin->id)->get();

        return response()->json([
            'message' => 'Category fetched successfully',
            'categories' => $categories
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin = $request->user(); 

        $category = Category::create([
            'admin_id' => $admin->id,
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    public function destroy($id, Request $request)
    {
        $admin = $request->user();

        $category = Category::where('id', $id)
                            ->where('admin_id', $admin->id)
                            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
