<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $type; // 'customer' or 'admin'

    public function __construct(Order $order, $type = 'customer')
    {
        $this->order = $order;
        $this->type = $type;
    }

    public function envelope(): Envelope
    {
        if ($this->type === 'admin') {
            return new Envelope(
                subject: '🚨 New Order #' . $this->order->id . ' Received',
            );
        }

        return new Envelope(
            subject: 'Order Confirmation #' . $this->order->id . ' - PageTurner',
        );
    }

    public function content(): Content
    {
        if ($this->type === 'admin') {
            return new Content(
                view: 'emails.admin.new-order',
            );
        }

        return new Content(
            view: 'emails.orders.confirmation',
        );
    }
}
