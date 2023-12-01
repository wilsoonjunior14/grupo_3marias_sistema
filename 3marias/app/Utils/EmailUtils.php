<?php

namespace App\Utils;

use App\Mail\SendRecoveryMail;
use Illuminate\Support\Facades\Mail;

class EmailUtils {

    /**
     * Sends email to recovery password.
     */
    public static function sendRecoveryPasswordEmail(mixed $user) {
        Mail::to($user["email"])
        ->send(new SendRecoveryMail($user));
    }
}