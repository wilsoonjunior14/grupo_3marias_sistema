<?php

namespace App\Http\Controllers;

use App\Validation\ModelValidator;
use BotMan\BotMan\Messages\Incoming\Answer;

class BotManController extends Controller
{

    /**
     * Botman logic.
     */
    public function handle()
    {
        $botman = app('botman');
        $botman->hears('{message}', function($botman, $message) {
            match($message) {
                "1" => $this->sayRegisterOnApp($botman),
                "2" => $this->sayAccessApp($botman),
                "3" => $this->sayRecoveryPassword($botman),
                "4" => $this->sayFeedbacksApp($botman),
                default => $botman->reply("Não entendi a sua resposta, por favor informe uma das opções. </br>")
            };
        });
   
        $botman->listen();
    }

    /**
     * Does the chatbot says the link to register on platform.
     */
    private function sayRegisterOnApp($botman) {
        $this->saysLink($botman, "http://localhost:3000#!/app", "para registrar-se na plataforma.");
    }

    /**
     * Does the chatbot says the link to access the platform.
     */
    private function sayAccessApp($botman) {
        $this->saysLink($botman, "http://localhost:3000#!/login", "para acessar a tela de login.");
    }

    /**
     * Does the chatbot process the email to recovery password.
     */
    private function sayRecoveryPassword($botman) {
        $botman->ask("Qual seu email? </br>", function (Answer $answer, $botman) {
            $emailRules = ["email" => "email:strict"];
            $emailRulesMessages = ["email.email" => "Email inválido."];
            $emailValidator = new ModelValidator($emailRules, $emailRulesMessages);
            $email = $answer->getText();

            $emailValidated = $emailValidator->validate(["email" => $email]);
            if ($emailValidated !== null){
                $botman->say("<p style='color: red'>O email informado é inválido. Por favor, tente novamente.</p>");
                return;
            }
            $botman->say("Okay! Este é seu email - ".$email);
        });
    }


    /**
     * Does the chatbot says the link to feedbacks screen.
     */
    private function sayFeedbacksApp($botman) {
        $this->saysLink($botman, "http://localhost:3000#!/login", "para deixar um feedback sobre nossa plataforma.");
    }

    private function saysLink($botman, string $link, string $complementText) {
        $botman->say("Por gentileza acesse esse <a target='_blank' href='{$link}'>link</a> {$complementText}. </br>", "");
    }
}
