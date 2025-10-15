<?php

namespace App\MessageHandler;

use App\Message\RegistrationConfirmationEmail;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class RegistrationConfirmationEmailHandler
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(RegistrationConfirmationEmail $message): void
    {
        $email = new TemplatedEmail()
            ->from('test@test.com')
            ->to($message->getTo())
            ->subject('Confirmation de votre compte')
            ->htmlTemplate('emails/confirmation.html.twig')
            ->context(['otp_code' => $message->getConfirmationCode(), 'user_email' => $message->getTo()]);

        $this->mailer->send($email);
    }
}
