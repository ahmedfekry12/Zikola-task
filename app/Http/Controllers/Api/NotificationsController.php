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

        $unreadNotifications = $user->unreadNotifications()->paginate($this->paginate);

        if ($unreadNotifications->isEmpty()) {
            return apiResponse(200, 'No unread notifications found', $unreadNotifications);
        }

        return apiResponse(200, 'Unread notifications retrieved successfully', NotificationResource::collection($unreadNotifications));
    }

    public function markAsRead(string $id)
    {
        $user = Auth::user();

        $notification = $user->unreadNotifications()->find($id);

        if (!$notification) {
            return apiResponse(404, 'Notification not found');
        }

        $notification->markAsRead();

        return apiResponse(200, 'Notification marked as read successfully', new NotificationResource($notification));
    }

    public function markAllAsRead()
    {
        $user = Auth::user();

        $unreadNotifications = $user->unreadNotifications();

        if (!$unreadNotifications->exists()) {
            return apiResponse(200, 'No unread notifications found');
        }

        $unreadNotifications->update([
            'read_at' => now()
        ]);

        return apiResponse(200, 'All notifications marked as read successfully');
    }
}
