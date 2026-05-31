<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StoresApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $stores = Store::active()->paginate();
        $stores = Store::paginate();

        return response()->json([
            'status' => true,
            'message' => 'Stores retrieved successfully',
            'data' => $stores
        ]);
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
    public function store(StoreRequest $request)
    {
        $user = Auth::id();
        $data = $request->safe()->except(['slug' , 'logo_image' , 'cover_image']);
        $data['user_id'] = $user;
        $data['slug'] = Str::slug($request->name);
        $data['logo_image'] = helper::uploadImage($request->logo_image , 'uploads/stores');
        $data['cover_image'] = helper::uploadImage($request->cover_image , 'uploads/stores');

        $store = Store::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Store created successfully',
            'data' => $store
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $store = Store::findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Store retrieved successfully',
            'data' => $store
        ]);
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
    public function update(StoreRequest $request, string $id)
    {
        $user = Auth::id();
        $store = Store::findOrFail($id);

        $data = $request->safe()->except(['slug' , 'logo_image' , 'cover_image']);
        $data['user_id'] = $user;
        $data['slug'] = Str::slug($request->name);

        if($request->hasFile('logo_image')){
            if (File::exists(public_path($store->logo_image))) {
                File::delete(public_path($store->logo_image));
            }

            $data['logo_image'] = helper::uploadImage($request->logo_image , 'uploads/stores');
        }

        if($request->hasFile('cover_image')){
            if (File::exists(public_path($store->cover_image))) {
                File::delete(public_path($store->cover_image));
            }

            $data['cover_image'] = helper::uploadImage($request->cover_image , 'uploads/stores');
        }

        $store->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Store updated successfully',
            'data' => $store
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $store = Store::findOrFail($id);

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'Store not found'
            ], 404);
        }
        
        $store->delete();

        return response()->json([
            'status' => true,
            'message' => 'Store deleted successfully'
        ]);
    }
}
