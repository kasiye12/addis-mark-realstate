<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Property;

class PropertyInquiryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public array $data;
    public Property $property;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->property = $data['property'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Property Inquiry: {$this->property->title} - Addis Mark Real Estate",
            from: config('mail.from.address'),
            replyTo: $this->data['email']
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.property-inquiry',
            with: [
                'data' => $this->data,
                'property' => $this->property
            ]
        );
    }
}