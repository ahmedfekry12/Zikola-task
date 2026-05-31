<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('user:id,name' , 'products:id,name,price')
            // ->pending()->withTrashed()
            ->select('id', 'number', 'status', 'user_id' , 'delivery', 'tax', 'discount', 'total')
            ->paginate();

        return helper::ApiResponse(200 , 'Orders retrieved successfully' , $orders);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        $userId = Auth::id();

        if (!$userId) {
            return helper::ApiResponse(404 , "You Should Login First");
        }

        $data = $request->validated();

        $data['user_id'] = $userId;

        $getStore = Product::findOrFail($request->products[0]['product_id'])->store;
        $data['store_id'] = $getStore->id;

        $products = [];

        $subtotal = 0;

        foreach ($request->products as $product) {

            $productModel = Product::findOrFail(
                $product['product_id']
            );

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

        $order = Order::create($data);

        $order->products()->attach($products);

        $storeId = $order->store_id;

        $order->notifications()->create([
            'sender_id' => Auth::id(),
            'sender_type' => User::class,
            
            'receiver_id' => $storeId,
            'receiver_type' => Store::class,

            'message' => "New order #{$order->number} has been created.",
        ]);

        return helper::ApiResponse(200 , 'Order created successfully' , $order->load('user:id,name', 'products:id,name,price'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with('user:id,name', 'products:id,name,price')
            ->select('id', 'number', 'status', 'delivery', 'tax', 'discount', 'total')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Order retrieved successfully',
            'data' => $order
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, string $id)
    {
        $userId = Auth::id();

        if (!$userId) {
            return helper::ApiResponse(404 , "You Should Login First");
        }

        $order = Order::findOrFail($id);

        $data = $request->validated();

        // $getStore = Product::findOrFail($request->products[0]['product_id'])->store;
        // $data['store_id'] = $getStore->id;

        $data['user_id'] = $userId;
        $data['store_id'] = $order->store_id;

        unset($data['products']);

        $products = [];

        $subtotal = 0;

        foreach ($request->products as $product) {

            $productModel = Product::findOrFail(
                $product['product_id']
            );

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

        return helper::ApiResponse(200 , 'Order updated successfully' , $order->load('user:id,name', 'products:id,name,price'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);

        if (!$order) {
            return helper::ApiResponse(404 , 'Order not found');
        }

        $order->delete();

        return helper::ApiResponse(200 , 'Order deleted successfully');
    }

    public function trashed()
    {
        $orders = Order::onlyTrashed()->get();

        return response()->json([
            'success' => true,
            'message' => 'Trashed orders retrieved successfully',
            'data' => $orders
        ], 200);
    }
}
