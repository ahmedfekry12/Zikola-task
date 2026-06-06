<?php

namespace App\Services;

use App\Helpers\helper;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Support\Facades\Auth;


class OrderService
{
    public function createOrder($request)
    {
        $user = Auth::user();

        if (!$user) {
            return helper::ApiResponse(404, "You Should Login First");
        }

        $date = $request->validated();
        $date['user_id'] = $user->id;

        $store = Product::findOrFail($request->products[0]['product_id'])->store;
        $date['store_id'] = $store->id;

        $products = [];
        $subtotal = 0;

        foreach ($request->products as $product) {
            $productModel = Product::findOrFail($product['product_id']);

            $price = (float) $productModel->price;
            $quantity = (int) $product['quantity'];

            $subtotal += $price * $quantity;

            $products[$product['product_id']] = [
                'price' => $price,
                'quantity' => $quantity,
                'options' => $product['options'] ?? null,
            ];
        }

        $delivery = $date['delivery'] ?? 0;
        $tax = $date['tax'] ?? 0;
        $discount = $date['discount'] ?? 0;

        $total = $subtotal + $delivery + $tax - $discount;

        $date['total'] = $total;

        $order = Order::create($date);

        $order->products()->attach($products);

        $storeOwner = User::findOrFail($store->user_id);

        $storeOwner->notify(
            new NewOrderNotification($order, "A new order #{$order->number} has been placed. Please check the order details.")
        );

        $user->notify(
            new NewOrderNotification($order, "Your order #{$order->number} has been sent to the restaurant.")
        );

        return $order;
    }

    public function updateOrder($request, $id)
    {
        $userId = Auth::id();

        if (!$userId) {
            return helper::ApiResponse(404, "You Should Login First");
        }

        $order = Order::findOrFail($id);

        $data = $request->validated();

        $data['user_id'] = $userId;
        $data['store_id'] = $order->store_id;

        unset($data['products']);

        $products = [];
        $subtotal = 0;

        foreach ($request->products as $product) {
            $productModel = Product::findOrFail($product['product_id']);

            $price = (float) $productModel->price;
            $quantity = (int) $product['quantity'];

            $subtotal += $price * $quantity;

            $products[$product['product_id']] = [
                'price' => $price,
                'quantity' => $quantity,
                'options' => $product['options'] ?? null,
            ];
        }

        $delivery = $data['delivery'] ?? 0;
        $tax = $data['tax'] ?? 0;
        $discount = $data['discount'] ?? 0;

        $total = $subtotal + $delivery + $tax - $discount;

        $data['total'] = $total;

        $order->update($data);

        $order->products()->sync($products);

        return $order;
    }
}