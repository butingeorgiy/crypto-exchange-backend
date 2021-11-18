<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCredentialsUpdating extends Mailable
{
    use Queueable, SerializesModels;

    public string $confirmationUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $confirmationUrl)
    {
        $this->confirmationUrl = $confirmationUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('mails.user-credentials-updating')
            ->subject('Изменение данных Безопасности!');
    }
}
