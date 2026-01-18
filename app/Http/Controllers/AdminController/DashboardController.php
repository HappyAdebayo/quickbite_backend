<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Menu;

class DashboardController extends Controller
{
   public function index()
      {
          $totalOrders = Order::count();
          $pendingOrders = Order::where('status', 'pending')->count();
          $availableFood = Menu::where('status', 'available')->count();
  
          $recentOrders = Order::with('items')
              ->orderBy('created_at', 'desc')
              ->take(5)
              ->get()
              ->map(function ($order) {
                  return [
                      'order_number' => $order->id,
                      'date' => $order->created_at->toDateString(),
                      'total_price' => $order->total_price,
                      'status' => ucfirst($order->status),
                      'items' => $order->items->map(function ($item) {
                          return [
                              'menu_name' => $item->menu->name,
                              'quantity' => $item->quantity,
                          ];
                      }),
                  ];
              });
  
          return response()->json([
              'total_orders' => $totalOrders,
              'pending_orders' => $pendingOrders,
              'available_food' => $availableFood,
              'recent_orders' => $recentOrders,
          ]);
      }  
}
