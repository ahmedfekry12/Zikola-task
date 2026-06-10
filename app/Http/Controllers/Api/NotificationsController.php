<?php

namespace App\Http\Controllers\Api;

use App\Helpers\helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function unReadNotifications()
    {
        $user = Auth::user();

        $unreadNotifications = $user->unreadNotifications()->paginate();

        if ($unreadNotifications->isEmpty()) {
            return helper::ApiResponse(200, 'No unread notifications found', $unreadNotifications);
        }

        return helper::ApiResponse(200, 'Unread notifications retrieved successfully', NotificationResource::collection($unreadNotifications));
    }

    public function markAsRead(string $id)
    {
        $user = Auth::user();

        $notification = $user->unreadNotifications()->find($id);

        if (!$notification) {
            return helper::ApiResponse(404, 'Notification not found');
        }

        $notification->markAsRead();

        return helper::ApiResponse(200, 'Notification marked as read successfully', new NotificationResource($notification));
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        $unreadNotifications = $user->unreadNotifications();

        if (!$unreadNotifications->exists()) {
            return helper::ApiResponse(200, 'No unread notifications found');
        }

        $unreadNotifications->update([
            'read_at' => now()
        ]);

        return helper::ApiResponse(200, 'All notifications marked as read successfully');
    }
}
