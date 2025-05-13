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
use App\Notifications\ChatMessageNotification;

class MessageController extends Controller
{
  
    

    

    public function showMessages($receiverId, $senderId)
    {
        $messages = Message::where(function ($q) use ($receiverId, $senderId) {
            $q->where('sender_id', $senderId)
              ->where('receiver_id', $receiverId);
        })->orWhere(function ($q) use ($receiverId, $senderId) {
            $q->where('sender_id', $receiverId)
              ->where('receiver_id', $senderId);
        })->orderBy('created_at')->get();
    
        return response()->json($messages);
    }


    


    public function send(Request $request)
    {
        // Save the message in the database
        $message = Message::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false,
        ]);
    
        // Fetch the player's name to include in the notification
        $player = Player::find($request->player_id);
    
        Notification::create([
            'coach_id' => $request->coach_id,
            'player_id' => $request->player_id,
            'message' => 'You have a New Conversation ' . ($player ? $player->player_name : 'Unknown'),
        ]);
    
        return response()->json([
            'message' => $message,
            'notification' => 'Notification sent successfully',
        ], 201);
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

