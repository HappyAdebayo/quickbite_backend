<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

use App\Models\Menu;
use App\Models\Category;

class CatalogController extends Controller
{
       public function categories()
    {
        $categoryNames = Category::pluck('name')->toArray();

        array_unshift($categoryNames, 'All');

        return response()->json([
            'message' => 'Categories fetched successfully',
            'categories' => $categoryNames
        ], 200);
    }


      public function menus()
    {
        $menus = Menu::with('category') 
            ->where('status', 'available')
            ->get()
            ->map(function ($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'description' => $menu->description,
                    'price' => $menu->price,
                    'status' => $menu->status,
                    'type' => $menu->category ? $menu->category->name : null,
                    'image' => $menu->image,
                ];
            });

        return response()->json([
            'message' => 'Menus fetched successfully',
            'menus' => $menus
        ], 200);
    }

}
