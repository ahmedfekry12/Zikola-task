<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $user = Auth::user();
        // $notifications = $user->notifications()->paginate();
        // return helper::ApiResponse([
        //     'success' => true,
        //     'message' => 'Notifications retrieved successfully',
        //     'data' => $notifications,
        // ] , 200);
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
    public function store(Request $request)
    {
        // $user = Auth::user();

        // $notification = Notification::create([
        //     'user_id' => $request->user_id,
        //     'order_id' => $request->order_id,
        //     'message' => $request->message,
        // ]);

        // return helper::ApiResponse(201 , 'Notification created successfully', $notification);
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
    public function update(Request $request, string $id)
    {
        // $user = Auth::user();
        // $notification = $user->notifications()->findOrFail($id);
        // $notification->update([
        //     'title' => $request->title,
        //     'body' => $request->body,
        // ]);

        // return helper::ApiResponse([
        //     'success' => true,
        //     'message' => 'Notification updated successfully',
        //     'data' => $notification,
        // ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
