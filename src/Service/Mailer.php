<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class Mailer
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($email, $username, $token, $subject, $htmlTemplate)
    {
        $email = (new TemplatedEmail())
            ->from('belarif.test@gmail.com')
            ->to($email)
            ->subject($subject)
            ->htmlTemplate($htmlTemplate)
            ->context([
                'expiration_date' => new \DateTime('+48 hours'),
                'username' => $username,
                'token' => $token,
            ]);
        $this->mailer->send($email);
    }
}