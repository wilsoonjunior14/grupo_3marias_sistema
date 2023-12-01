<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRecoveryMail extends Mailable
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
            ->subject("3Marias - Recuperação de Senha")
            ->from(env('MAIL_USERNAME', 'wjunior_msn@hotmail.com'))
            ->view('recovery-password')
            ->with('data', $this->user);
    }

}
