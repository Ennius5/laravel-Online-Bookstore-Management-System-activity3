<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $oldStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, $oldStatus)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->order->status) {
            'processing' => '🔄 Your Order #' . $this->order->id . ' is Being Processed',
            'shipped' => '📦 Your Order #' . $this->order->id . ' Has Been Shipped!',
            'completed' => '✅ Your Order #' . $this->order->id . ' is Complete',
            'cancelled' => '❌ Order #' . $this->order->id . ' Update',
            default => 'Order #' . $this->order->id . ' Status Update'
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.status-update',
        );
    }
}
