<?php

namespace App\Http\Controllers;

use App\Models\Notification;

use Illuminate\Http\Request;
use App\Models\PlayerNotification;

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
        $notifications = PlayerNotification::with('coach')->where('player_id', $player_id)
        ->where('is_read', 0)
        ->orderBy('created_at', 'desc')
        ->get();


        return response()->json([
            'status' => true,
            'playernotifications' => $notifications,
        ]);
    }

    public function getNotificationCoach($coach_id){
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

        Notification::where('coach_id', $coach_id)->orWhere('player_id',$coach_id)
            ->where('id', $notificationId)
            ->update(['is_read' => 1]);

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read.'
        ]);
    }

    public function markPlayerNotificationAsRead(Request $request, $player_id)
    {
        $notificationId = $request->input('id');

        PlayerNotification::where('player_id', $player_id)
            ->where('id', $notificationId)
            ->update(['is_read' => 1]);

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read.'
        ]);
    }


}
