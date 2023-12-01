<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;
    private User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user) 
    {
        $this->user = $user;
    }

    public function build() {
        return $this
            ->from(env('MAIL_USERNAME', 'busqueiapplication@gmail.com'))
            ->view('recovery-password');
    }

}
