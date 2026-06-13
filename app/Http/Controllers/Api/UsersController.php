<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate($this->paginate);
        
        return apiResponse(200, 'Users retrieved successfully', $users);
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
    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());

        return apiResponse(201, 'User created successfully', $user);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return apiResponse(200, 'User retrieved successfully', $user);
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
    public function update(UserRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());

        return apiResponse(200, 'User updated successfully', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return apiResponse(200, 'User deleted successfully', null);
    }
}
