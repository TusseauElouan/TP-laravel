<?php

namespace App\Mail;

use App\Models\Absence;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class AbsenceMail extends Mailable
{
    use Queueable, SerializesModels;

    // Déclare la propriété publique ou protégée
    public $absence;

    /**
     * Create a new message instance.
     */
    public function __construct(Absence $absence)
    {
        $this->absence = $absence; // Initialise l'absence dans le constructeur
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Absence Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.absence',
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

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->markdown('emails.absence')
                    ->subject('Validation de l\'absence de ' . $this->absence->user->nom)
                    ->with('absence', $this->absence); // Passe les données de l'absence à la vue
    }
}

