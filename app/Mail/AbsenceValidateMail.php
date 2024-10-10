<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Absence;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class AbsenceValidateMail extends Mailable
{
    use Queueable, SerializesModels;

    public Absence $absence;
    public User $user;
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
            subject: 'Absence Validate Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.absence_validate',
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
     * Summary of build
     * @return AbsenceValidateMail|void
     */
    public function build()
    {
        if (Auth::user()->isAn('admin')) {
            return $this->markdown('emails.absence_validate')
                ->subject('Validation de l\'absence de '. Auth::user()->prenom .' '. Auth::user()->nom)
                ->with('absence', $this->absence); // Passe les données de l'absence à la vue
        }
    }
}
