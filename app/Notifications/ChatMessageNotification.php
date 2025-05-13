<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ChatMessageNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $sender;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $sender)
    {
        $this->message = $message;
        $this->sender = $sender;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Store notification data in the database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name ?? 'Unknown',
            'sender_type' => get_class($this->sender),
            'sent_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * (Optional) Represent notification as array.
     */
    public function toArray($notifiable): array
    {
        return $this->toDatabase($notifiable); // Optional: mirror database version
    }
}
