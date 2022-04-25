<?php

namespace App\Mail;

use Base\View;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Email;

abstract class AbstractMail
{
    private TransportInterface $transport;
    protected Mailer $mailer;
    protected View $view;
    protected $email;

    public function __construct()
    {
        $this->transport = Transport::fromDsn('smtp://'.SMTP_LOGIN.':'.SMTP_PASSWORD.'@'.SMTP_SERVER.':'.SMTP_PORT.'');
        $this->mailer = new Mailer($this->transport);
        $this->email = (new Email())->from(SMTP_LOGIN);
        $this->view = new View();
    }
}
