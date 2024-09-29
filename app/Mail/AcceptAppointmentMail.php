<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class AcceptAppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    protected $appointment;
    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận lịch hẹn.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $appointmentData = Carbon::parse($this->appointment->appointment_data);
        return new Content(
            view: 'appointmentMail',
            with: [
                'title' => 'Thông tin lịch hẹn',
                'data' => $this->appointment,
                'date' => $appointmentData,
                'location' => '123 Đà Nẵng',
            ],
        );
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
