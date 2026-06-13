<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate($this->paginate);

        if($categories->isEmpty()){
            return apiResponse(200, 'No categories found', $categories);
        }

        return apiResponse(200, 'Categories retrieved successfully', $categories);
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
    public function store(CategoryRequest $request)
    {
        $data = $request->safe()->except(['image' , 'slug']);

        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $data['image'] = uploadImage($request->image , 'uploads/categories');
        }

        $category = Category::create($data);

        return apiResponse(201, 'Category created successfully', $category);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);

        if(!$category){
            return apiResponse(404, 'Category not found');
        }

        return apiResponse(200, 'Category retrieved successfully', $category);
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
    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::findOrFail($id);

        if(!$category){
            return apiResponse(404, 'Category not found');
        }

        $data = $request->safe()->except(['image' , 'slug']);

        $data['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            if (File::exists(public_path($category->image))) {
                File::delete(public_path($category->image));
            }

            $data['image'] = uploadImage($request->image , 'uploads/categories');
        }

        $category->update($data);

        return apiResponse(200, 'Category updated successfully', $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        if(!$category){
            return apiResponse(404, 'Category not found');
        }

        $category->delete();

        return apiResponse(200, 'Category deleted successfully', $category);
    }
}
