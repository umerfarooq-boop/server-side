<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\player;
use App\Models\Message;
use App\Events\MessageSent;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\CoachSchedule;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        // Save the message in the database
        $message = Message::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);
    
     
    
        return response()->json([
            'message' => $message,
        ], 201);
    }
    

    

    // public function showMessages($receiverId, $senderId)
    // {
    //     $messages = Message::where(function ($q) use ($receiverId, $senderId) {
    //         $q->where('sender_id', $senderId)
    //           ->where('receiver_id', $receiverId);
    //     })->orWhere(function ($q) use ($receiverId, $senderId) {
    //         $q->where('sender_id', $receiverId)
    //           ->where('receiver_id', $senderId);
    //     })->orderBy('created_at')->get();
    
    //     return response()->json($messages);
    // }
    
    public function showMessages($receiverId, $senderId)
{
    // Get messages between users
    $messages = Message::where(function ($q) use ($receiverId, $senderId) {
        $q->where('sender_id', $senderId)
          ->where('receiver_id', $receiverId);
    })->orWhere(function ($q) use ($receiverId, $senderId) {
        $q->where('sender_id', $receiverId)
          ->where('receiver_id', $senderId);
    })->orderBy('created_at')->get();

    // Mark unread messages as read
    Message::where('sender_id', $senderId)
        ->where('receiver_id', $receiverId)
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return response()->json($messages);
}

public function unreadCount($userId)
{
    $count = Message::where('receiver_id', $userId)
        ->where('is_read', false)
        ->count();

    return response()->json(['unread' => $count]);
}


    public function GetBookedRecord($id)
    {
        $ChatSidebar = CoachSchedule::with(['player', 'coach'])
            ->where(function ($query) use ($id) {
                $query->where('player_id', $id)
                      ->orWhere('coach_id', $id);
            })
            ->where('status', 'booked')
            ->get();
    
        return response()->json([
            'success' => true,
            'message' => 'Record Get Successfully',
            'sidebar' => $ChatSidebar,
        ], 201);
    }


    
}

