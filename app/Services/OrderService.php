<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function placeOrder(array $cart)
    {
        $total = 0;
        $orderItems = [];

        foreach ($cart as $item) {
            $menu = Menu::find($item['menu_id']);

            if (!$menu || $menu->status !== 'available') {
                continue;
            }

            $price = $menu->price * $item['quantity'];
            $total += $price;

            $orderItems[] = [
                'menu_id' => $menu->id,
                'quantity' => $item['quantity'],
                'price' => $menu->price,
            ];
        }

        if (empty($orderItems)) {
            throw new \Exception('No available menu items in cart');
        }

        return DB::transaction(function () use ($total, $orderItems) {
            $order = Order::create([
                'total_price' => $total,
                'status' => 'pending',
            ]);

            $order->items()->createMany($orderItems);

            return $order->load('items'); 
        });
    }
}
