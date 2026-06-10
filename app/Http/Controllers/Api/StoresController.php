<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequest;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class StoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $stores = $user->stores()->paginate();
        
        // $stores = Store::active()->paginate();
        // $stores = Store::paginate();

        return helper::ApiResponse(200, 'Stores retrieved successfully', StoreResource::collection($stores));
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

        return helper::ApiResponse(200, 'Store created successfully', new StoreResource($store));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $store = $user->stores()->findOrFail($id);

        if (!$store) {
            return helper::ApiResponse(404, 'Store not found');
        }

        return helper::ApiResponse(200, 'Store retrieved successfully', new StoreResource($store));
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
        $user = Auth::user();
        $store = $user->stores()->findOrFail($id);

        if (!$store) {
            return helper::ApiResponse(404, 'Store not found');
        }

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

        return helper::ApiResponse(200, 'Store updated successfully', new StoreResource($store));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $store = $user->stores()->findOrFail($id);

        if (!$store) {
            return helper::ApiResponse(404, 'Store not found');
        }
        
        $store->delete();

        return helper::ApiResponse(200, 'Store deleted successfully', null);
        
    }
}

