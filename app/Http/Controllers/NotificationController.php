<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function getNotifications($coach_id)
    {
        $notifications = Notification::with('player')->where('coach_id', $coach_id)
        ->where('is_read', 0)
        ->orderBy('created_at', 'desc')
        ->get();


        return response()->json([
            'status' => true,
            'notifications' => $notifications,
        ]);
    }

    public function getNotificationsPlayer($player_id)
    {
        $notifications = Notification::with('coach')->where('player_id', $player_id)
        ->where('is_read', 0)
        ->orderBy('created_at', 'desc')
        ->get();


        return response()->json([
            'status' => true,
            'notifications' => $notifications,
        ]);
    }

    // public function NotificationImage

    public function markNotificationAsRead(Request $request, $coach_id)
    {
        $notificationId = $request->input('id');

        Notification::where('coach_id', $coach_id)
            ->where('id', $notificationId)
            ->update(['is_read' => 1]);

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read.'
        ]);
    }


}
