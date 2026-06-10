<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{

    public function __construct(private OrderService $orderService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if(!$user) {
            return helper::ApiResponse(403, 'Unauthorized');
        }

        $orders = $user->orders()->with('user:id,name', 'products:id,name,price')
            // ->pending()->withTrashed()
            ->select('id', 'number', 'status', 'user_id', 'delivery', 'tax', 'discount', 'total')
            ->paginate();

        if ($orders->isEmpty()) {
            return helper::ApiResponse(404, 'No orders found');
        }

        return helper::ApiResponse(200, 'Orders retrieved successfully', OrderResource::collection($orders));
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
        $order = $this->orderService->createOrder($request);

        return helper::ApiResponse(200, 'Order created successfully', new OrderResource($order->load('user:id,name', 'products:id,name,price')));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        
        $order = $user->orders()->with('user:id,name', 'products:id,name,price')
            ->select('id', 'number', 'status', 'delivery', 'tax', 'discount', 'total')
            ->findOrFail($id);

        return helper::ApiResponse(200, 'Order retrieved successfully', new OrderResource($order));
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
        $order = $this->orderService->updateOrder($request, $id);

        return helper::ApiResponse(200, 'Order updated successfully', new OrderResource($order->load('user:id,name', 'products:id,name,price')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $order = $user->orders()->findOrFail($id);

        if (!$order) {
            return helper::ApiResponse(404, 'Order not found');
        }

        $order->delete();

        return helper::ApiResponse(200, 'Order deleted successfully' . $order->number);
    }

    public function trashed()
    {
        $user = Auth::user();
        $orders = $user->orders()->onlyTrashed()->get();

        if ($orders->isEmpty()) {
            return helper::ApiResponse(404, 'No trashed orders found');
        }

        return helper::ApiResponse(200, 'Trashed orders retrieved successfully', OrderResource::collection($orders));
    }
}
