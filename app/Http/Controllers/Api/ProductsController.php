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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $storeId)
    {
        $user = Auth::user();

        if (!$user) {
            return apiResponse(404, "You Should Login First");
        }

        Gate::authorize('is_admin');

        Gate::authorize('viewAny', Product::class);

        // $products = Product::expensive()->paginate();
        $products = Product::where('store_id', $storeId)->paginate($this->paginate);

        return apiResponse(200, 'Products retrieved successfully', ProductResource::collection($products));
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
            return apiResponse(404, "You Should Be A Store Owner To Create Product");
        }

        Gate::authorize('create', Product::class);

        $data = $request->safe()->except(['image', 'slug']);

        $data['store_id'] = $store->id;

        $data['options'] = $data['options'] ?? [];

        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->image, 'uploads/products');
        }

        $product = Product::create($data);

        return apiResponse(200, 'Product created successfully', new ProductResource($product));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);

        Gate::authorize('view', $product);

        return apiResponse(200, 'Product retrieved successfully', new ProductResource($product));
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
            return apiResponse(404, "You Should Be A Store Owner To Update Product");
        }

        $product = $store->products()->findOrFail($id);

        Gate::authorize('update', $product);

        $data = $request->safe()->except(['image', 'slug']);

        $data['store_id'] = $store->id;

        $data['options'] = $data['options'] ?? [];

        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if (File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }

            $data['image'] = uploadImage($request->image, 'uploads/products');
        }

        if ($product) {
            $product->update($data);
            return apiResponse(200, 'Product updated successfully', new ProductResource($product));
        } else {
            return apiResponse(404, 'Product not found');
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
            return apiResponse(404, "You Should Be A Store Owner To Delete Product");
        }

        $product = $store->products()->findOrFail($id);
        if (!$product) {
            return apiResponse(404, 'Product not found');
        }

        Gate::authorize('delete', $product);

        $product->delete();
        return apiResponse(200, 'Product deleted successfully');
    }

    public function trashed()
    {
        $products = Product::onlyTrashed()->get();

        return apiResponse(200, 'Trashed products retrieved successfully', ProductResource::collection($products));
    }
}
