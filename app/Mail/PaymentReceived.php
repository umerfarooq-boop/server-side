<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;  

class PaymentReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $player;
    public $coach;
    public $amount;
    public $pdf;
    public $payment_id;
    /**
     * Create a new message instance.
     */
    public function __construct($player, $coach, $amount, $pdf, $payment_id)
    {
        $this->player = $player;
        $this->coach = $coach;
        $this->amount = $amount;
        $this->pdf = $pdf;
        $this->payment_id = $payment_id;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Received',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-received',
        );
    }

    public function build()
    {
        return $this->subject('Payment Invoice Received')
            ->view('emails.payment-received')
            ->with([
                'player' => $this->player,
                'coach' => $this->coach,
                'amount' => $this->amount,
                'payment_id' => $this->payment_id, // 🛠️ Send to view
            ])
            ->attachData($this->pdf->output(), 'invoice.pdf', [
                'mime' => 'application/pdf',
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
