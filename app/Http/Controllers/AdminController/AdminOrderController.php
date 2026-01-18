<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.menu')->orderBy('created_at', 'desc')->get();

        $formattedOrders = $orders->map(function ($order) {
            return [
                'order_number' => $order->id,
                'status' => $order->status,
                'date' => $order->created_at->toDateString(),
                'total_price' => $order->total_price,
                'items' => $order->items->map(function ($item) {
                    return [
                        'menu_id' => $item->menu_id,
                        'menu_name' => $item->menu->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price, 
                    ];
                }),
            ];
        });

        return response()->json([
            'message' => 'Orders fetched successfully',
            'orders' => $formattedOrders
        ]);
    }

    public function updateStatus(Request $request, $orderId)
{
    $validator = Validator::make($request->all(), [
        'status' => 'required|in:processing,completed',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    $order = Order::find($orderId);

    if (!$order) {
        return response()->json([
            'message' => 'Order not found'
        ], 404);
    }

    $order->status = $request->status;
    $order->save();

    return response()->json([
        'message' => 'Order status updated successfully',
        'order' => $order
    ]);
}
}
