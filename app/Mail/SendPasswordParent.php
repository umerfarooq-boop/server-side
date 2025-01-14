<?php

namespace App\Mail;

use App\Models\User;
use App\Models\player;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendPasswordParent extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $player;
    public $plainPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Player $player, $plainPassword)
    {
        $this->user = $user;
        $this->player = $player;
        $this->plainPassword = $plainPassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Password Parent',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sendPasswordParent',
        );
    }

    public function build()
    {
        return $this->subject('Your Login Password')
                    ->view('emails.sendPasswordParent')
                    ->with([
                        'user' => $this->user,
                        'player' => $this->player,
                        'plainPassword' => $this->plainPassword,
                    ]);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
