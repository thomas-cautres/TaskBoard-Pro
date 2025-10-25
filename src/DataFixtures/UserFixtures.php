<?php

declare(strict_types=1);

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
        foreach ($this->getUsers() as $userArray) {
            $user = new User();
            $user
                ->setFirstName($userArray[0])
                ->setLastName($userArray[1])
                ->setEmail($userArray[2])
                ->setPassword($this->passwordHasher->hashPassword($user, $userArray[3]))
                ->setConfirmed($userArray[4])
                ->setConfirmationCode($userArray[5]);

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUsers(): \Iterator
    {
        yield ['John', 'Doe', 'user-confirmed@domain.com', 'test1234', true, '1234'];
        yield ['Jane', 'Doe', 'user-unconfirmed@domain.com', 'test1234', false, '1234'];
    }
}
