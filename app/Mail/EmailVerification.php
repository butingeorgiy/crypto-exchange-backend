<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public string $verificationUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Build the message.
     *
     * @return static
     */
    public function build(): static
    {
        return $this->view('mails.email-verification')
            ->subject('Подтверждение E-mail адреса! Coin Exchange');
    }
}
