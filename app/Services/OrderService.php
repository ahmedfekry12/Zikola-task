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
            return apiResponse(404, "You Should Login First");
        }

        $data = $this->prepareOrderData($request, $user);

        [$products, $subtotal] = $this->prepareProducts($request->products);

        $total = $this->calculateTotal($subtotal, $data['delivery'], $data['tax'], $data['discount']);

        $data['total'] = $total;

        $order = $this->storeOrder($data);

        $this->attachProducts($order , $products);

        $this->createNotification($order, $data['store']->user, $user);

        return $order;
    }

    public function updateOrder($request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return apiResponse(404, "You Should Login First");
        }

        $order = Order::findOrFail($id);

        $data = $request->validated();

        $data['user_id'] = $user->id;
        $data['store_id'] = $order->store_id;

        unset($data['products']);

        [$products, $subtotal] = $this->prepareProducts($request->products);

        $total = $this->calculateTotal($subtotal, $data['delivery'], $data['tax'], $data['discount']);

        $data['total'] = $total;

        $order = $this->update($data , $id);

        $this->syncProducts($order , $products);

        return $order;
    }

    function prepareOrderData($request , $user)
    {
        $data = $request->validated();

        $data['user_id'] = $user->id;

        $store = Product::findOrFail($request->products[0]['product_id'])->store;
        $data['store_id'] = $store->id;
        $data['store'] = $store;

        return $data;
    }

    function prepareProducts(array $requestProducts)
    {
        $products = [];
        $subtotal = 0;

        foreach ($requestProducts as $product) {
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
        return [$products, $subtotal];
    }

    function calculateTotal($subtotal, $delivery, $tax, $discount)
    {
        return $subtotal + $delivery + $tax - $discount;
    }

    function storeOrder($data)
    {
        return Order::create($data);
    }

    function attachProducts($order , $products)
    {
        $order->products()->attach($products);
    }

    function createNotification($order, $storeOwner, $user)
    {
        $storeOwner->notify(
            new NewOrderNotification($order, "A new order #{$order->number} has been placed. Please check the order details.")
        );

        $user->notify(
            new NewOrderNotification($order, "Your order #{$order->number} has been sent to the restaurant.")
        );
    }

    function update($data, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($data);

        return $order;
    }

    function syncProducts($order , $products)
    {
        return $order->products()->sync($products);
    }
}