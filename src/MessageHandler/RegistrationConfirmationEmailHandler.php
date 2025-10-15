<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\RegistrationConfirmationEmail;
use CoopTilleuls\UrlSignerBundle\UrlSigner\UrlSignerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsMessageHandler]
final readonly class RegistrationConfirmationEmailHandler
{
    public function __construct(
        private MailerInterface $mailer,
        #[Autowire(env: 'MAILER_FROM')]
        private string $from,
        #[Autowire(env: 'CONFIRMATION_LINK_LIFETIME')]
        private int $confirmationLinkLifetime,
        private UrlSignerInterface $urlSigner,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(RegistrationConfirmationEmail $message): void
    {
        $confirmUrl = $this->urlSigner->sign($this->urlGenerator->generate('confirm', ['email' => $message->getTo()], UrlGeneratorInterface::ABSOLUTE_URL), $this->confirmationLinkLifetime);

        $email = new TemplatedEmail()
            ->from($this->from)
            ->to($message->getTo())
            ->subject('Account confirmation - TaskBoard Pro')
            ->htmlTemplate('emails/confirmation.html.twig')
            ->context(['otp_code' => $message->getConfirmationCode(), 'confirmation_url' => $confirmUrl]);

        $this->mailer->send($email);
    }
}
