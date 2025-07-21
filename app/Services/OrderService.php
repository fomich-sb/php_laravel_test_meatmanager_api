<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'user_id' => $data['user_id'],
                'total' => 0,
                'comment' => $data['comment'] ?? null,
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                $total += $product->price * $item['quantity'];
            }

            $order->update(['total' => $total]);

            return $order->load('items');
        });
    }

    public function getUserOrders(int $userId): Collection
    {
        return Order::with('items.product')
            ->where('user_id', $userId)
            ->get();
    }
}