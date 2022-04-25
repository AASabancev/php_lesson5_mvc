<?php

namespace App\Mail;

use Base\View;

class RegisterMail extends AbstractMail
{
    function sendMail(string $emailTo){
        $html = $this->view->render('Email/registered.phtml', [
            'email' => $emailTo,
        ]);

        $subject = 'Вы успешно зарегистрированы!';

        $this->email
            ->to($emailTo)
            ->subject($subject)
            ->html($html);

        $this->mailer->send($this->email);
    }
}
