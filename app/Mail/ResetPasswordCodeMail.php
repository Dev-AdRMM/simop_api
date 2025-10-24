<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;

    # Create a new message instance.
    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->subject('Código para redefinir sua senha')
            ->view('simop_serverSide.pages.auth.emails.verify_email_user')
            ->with(['code' => $this->code]);
    }

    # Get the attachments for the message.
    public function attachments(): array
    {
        return [];
    }
}
