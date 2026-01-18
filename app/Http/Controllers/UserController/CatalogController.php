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
        $categories = Category::all();

        return response()->json([
            'message' => 'Category fetched successfully',
            'categories' => $categories
        ], 200);
    }

       public function menus()
    {
        $menu = Menu::where('status', 'available')->get();

        return response()->json([
            'message' => 'Menu fetched successfully',
            'menu' => $menu
        ], 200);
    }
}
