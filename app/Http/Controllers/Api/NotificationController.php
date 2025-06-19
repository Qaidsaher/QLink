<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends ApiController
{
    /**
     * Display a listing of notifications for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();

        $notifications = $user->notifications()->paginate(15);

        return $this->sendResponse($notifications, 'Notifications retrieved successfully.');
    }

    /**
     * Mark a single notification as read.
     *
     * @param string $id Notification ID
     */
    public function markRead(string $id)
    {
        $user = Auth::user();

        $notification = $user->notifications()->where('id', $id)->first();

        if (!$notification) {
            return $this->sendNotFound('Notification not found.');
        }

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        return $this->sendResponse([], 'Notification marked as read.');
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllRead()
    {
        $user = Auth::user();

        $unread = $user->unreadNotifications;

        if ($unread->isEmpty()) {
            return $this->sendResponse([], 'No unread notifications found.');
        }

        $unread->markAsRead();

        return $this->sendResponse([], 'All notifications marked as read.');
    }
}
