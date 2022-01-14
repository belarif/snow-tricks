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

    public function sendEmail($email, $username, $token)
    {
        $email = (new TemplatedEmail())
            ->from('belarif.test@gmail.com')
            ->to($email)
            ->subject('activer votre compte SnowTricks')
            ->htmlTemplate('/emails/activation.html.twig')
            ->context([
                'expiration_date' => new \DateTime('+48 hours'),
                'username' => $username,
                'token' => $token,
            ]);
        $this->mailer->send($email);
    }
}