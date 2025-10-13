<?php

namespace App\Event\Security;

use App\Repository\UserRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecuritySubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher, private readonly UserRepository $userRepository)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::class => 'onUserRegistered',
        ];
    }

    public function onUserRegistered(UserRegisteredEvent $event): void
    {
        $user = $event->getUser();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );
        $user->setPassword($hashedPassword);

        $this->userRepository->persist($user);
    }
}
