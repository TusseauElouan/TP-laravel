<?php

namespace App\Mail;

use App\Models\Absence;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InfoGeneriqueMail extends Mailable
{
    use Queueable, SerializesModels;

    protected string $content;

    /** @var array<string, mixed> */
    protected array $details;
    protected bool $admin;

    protected Absence $absence;
    /**
     * Create a new message instance.
     *
     * @param  array<string, mixed>  $details
     */
    public function __construct(string $subject, string $content, array $details = [], Absence $absence,bool $admin = false)
    {
        $this->subject = $subject;
        $this->content = $content;
        $this->details = $details;
        $this->admin = $admin;
        $this->absence = $absence;
    }

    /**
     * Summary of build
     * @return \App\Mail\InfoGeneriqueMail
     */
    public function build(): self
    {
        if ($this->admin) {
            return $this->view('emails.mail_generique_admin')
                ->with('subject', $this->subject)
                ->with('content', $this->content)
                ->with('details', $this->details)
                ->with('absence', $this->absence->id);
        }
        else{
            return $this->view('emails.mail_generique')
            ->with('subject', $this->subject)
            ->with('content', $this->content)
            ->with('details', $this->details);
        }
    }
}
