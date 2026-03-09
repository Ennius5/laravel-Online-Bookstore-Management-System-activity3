<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReviewNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $review;
    public $type; // 'customer' or 'admin'

    public function __construct(Review $review, $type = 'customer')
    {
        $this->review = $review;
        $this->type = $type;
    }

    public function envelope(): Envelope
    {
        if ($this->type === 'admin') {
            return new Envelope(
                subject: 'Updated Review: ' . $this->review->book->title,
            );
        }

        return new Envelope(
            subject: 'You have updated your review for ' . $this->review->book->title,
        );
    }

    public function content(): Content
    {
        if ($this->type === 'admin') {
            return new Content(
                view: 'emails.reviews.status-update',
            );
        }

        return new Content(
            view: 'emails.reviews.confirmation',
        );
    }
}
