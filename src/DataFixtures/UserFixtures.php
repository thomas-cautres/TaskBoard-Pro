<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user
            ->setEmail('user-confirmed@domain.com')
            ->setPassword($this->passwordHasher->hashPassword($user, 'test1234'))
            ->setConfirmed(true)
            ->setConfirmationCode('1234');

        $manager->persist($user);

        $user = new User();
        $user
            ->setEmail('user-unconfirmed@domain.com')
            ->setPassword($this->passwordHasher->hashPassword($user, 'test1234'))
            ->setConfirmed(false)
            ->setConfirmationCode('1234');

        $manager->persist($user);
        $manager->flush();
    }
}
