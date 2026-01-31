<?php

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

use App\Services\OrderService;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    
    public function placeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart' => 'required|array|min:1',
            'cart.*.menu_id' => 'required|exists:menus,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

            $order = $this->orderService->placeOrder($request->cart);

            return response()->json([
                'message' => 'Order placed successfully',
                'order_id' => $order->id,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'items' => $order->items,
            ], 201);
    }

    public function show($orderId)
    {
        $order = Order::with('items.menu')->find($orderId);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Order fetched successfully',
            'order' => [
                'id' => $order->id,
                'status' => $order->status,
                'total_price' => $order->total_price,
                'created_at' => $order->created_at->toDateTimeString(),
                'items' => $order->items->map(function ($item) {
                    return [
                        'menu_id' => $item->menu_id,
                        'menu_name' => $item->menu->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ];
                }),
            ]
        ]);
    }
}
