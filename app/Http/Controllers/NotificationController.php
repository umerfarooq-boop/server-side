<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function getNotifications($coach_id)
    {
        $notifications = Notification::where('coach_id', $coach_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'notifications' => $notifications,
        ]);
    }
}
