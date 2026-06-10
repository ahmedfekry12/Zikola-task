<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // if(!$user->stores->whereHas('products')->exists()) {
        //     return helper::ApiResponse(403, 'No products found for this user');
        // }

        $hasProducts = Product::whereHas('store', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->exists();

        if (!$hasProducts) {
            return helper::ApiResponse(403, 'No products found');
        }

        // $products = Product::expensive()->paginate();
        $products = Product::paginate();
        
        return helper::ApiResponse(200, 'Products retrieved successfully', ProductResource::collection($products));
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
    public function store(ProductRequest $request)
    {
        $user = Auth::user();
        $store = $user->stores->first();

        if (!$store) {
            return helper::ApiResponse(404 , "You Should Be A Store Owner To Create Product");
        }
        
        $data = $request->safe()->except(['image' , 'slug']);

        $data['store_id'] = $store->id;
        
        $data['options'] = $data['options'] ?? [];

        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = helper::uploadImage($request->image , 'uploads/products');
        }

        $product = Product::create($data);

        return helper::ApiResponse(200, 'Product created successfully', new ProductResource($product));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        return helper::ApiResponse(200, 'Product retrieved successfully', new ProductResource($product));
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
    public function update(ProductRequest $request, string $id)
    {
        $user = Auth::user();
        $store = $user->stores->first();

        if (!$store) {
            return helper::ApiResponse(404 , "You Should Be A Store Owner To Update Product");
        }

        $product = Product::findOrFail($id);

        $data = $request->safe()->except(['image' , 'slug']);

        $data['store_id'] = $store->id;

        $data['options'] = $data['options'] ?? [];
        
        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if (File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            $data['image'] = helper::uploadImage($request->image , 'uploads/products');
        }

        if ($product) {
            $product->update($data);
            return helper::ApiResponse(200 , 'Product updated successfully' , new ProductResource($product));
        } else {
            return helper::ApiResponse(404 , 'Product not found');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $store = $user->stores->first();

        if (!$store) {
            return helper::ApiResponse(404 , "You Should Be A Store Owner To Delete Product");
        }

        $product = $store->products()->findOrFail($id);
        if (!$product) {
            return helper::ApiResponse(404 , 'Product not found');
        }

        $product->delete();
        return helper::ApiResponse(200 , 'Product deleted successfully');
    }

    public function trashed()
    {
        $products = Product::onlyTrashed()->get();

        return helper::ApiResponse(200 , 'Trashed products retrieved successfully', ProductResource::collection($products));
    }
}