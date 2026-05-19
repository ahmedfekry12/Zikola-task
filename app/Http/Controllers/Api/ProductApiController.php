<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::expensive()->paginate();
        
        return response()->json([
            'data' => $products,
            'message' => 'Products retrieved successfully'
        ] , 200);
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
        $data = $request->validated();
        
        $data['options'] = $data['options'] ?? [];

        $product = Product::create($data);

        return response()->json([
            'data' => $product,
            'message' => 'Product created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $data = $request->validated();

        $data['options'] = $data['options'] ?? [];

        $product = Product::findOrFail($id);

        if ($product) {
            $product->update($data);
            return response()->json([
                'data' => $product,
                'message' => 'Product updated successfully'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->delete();
        return response()->json([
            'message' => 'Product deleted successfully'
        ], 204);
    }

    public function trashed()
    {
        $products = Product::onlyTrashed()->get();

        return response()->json([
            'data' => $products,
            'message' => 'Trashed products retrieved successfully'
        ], 200);
    }
}