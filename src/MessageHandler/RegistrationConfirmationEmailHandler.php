<?php

namespace App\MessageHandler;

use App\Message\RegistrationConfirmationEmail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RegistrationConfirmationEmailHandler
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function __invoke(RegistrationConfirmationEmail $message): void
    {
        $email = new TemplatedEmail()
            ->from('test@test.com')
            ->to('test@test.com')
            ->subject('Test')
            ->text('test');

        $this->mailer->send($email);
    }
}
