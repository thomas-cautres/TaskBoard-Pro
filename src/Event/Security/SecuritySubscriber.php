<?php

namespace App\Event\Security;

use App\Message\RegistrationConfirmationEmail;
use App\Repository\UserRepository;
use Random\RandomException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class SecuritySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
        private MessageBusInterface $bus,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onUserRegistered',
            UserConfirmedEvent::class => 'onUserConfirmed',
        ];
    }

    /**
     * @throws RandomException
     */
    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $user = $event->getUser();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );

        $user
            ->setPassword($hashedPassword)
            ->setConfirmationCode(random_int(1000, 9999));

        $this->userRepository->persist($user);

        $this->bus->dispatch(new RegistrationConfirmationEmail());
    }

    public function onUserConfirmed(UserConfirmedEvent $event): void
    {
        $this->userRepository->persist($event->getUser()->setConfirmed(true));
    }
}
