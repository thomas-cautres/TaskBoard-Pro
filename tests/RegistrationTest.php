<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationTest extends WebTestCase
{
    public function testIsSuccessful(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/registration');
        $this->assertResponseIsSuccessful();

        $crawler = $client->submitForm('signup-btn', [
            'registration[email]' => 'test@test.com',
            'registration[password][first]' => 'testtest123',
            'registration[password][second]' => 'testtest123',
        ]);

        $this->assertResponseRedirects('/login');

        $container = static::$kernel->getContainer();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine')->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => 'test@test.com']);

        $this->assertInstanceOf(User::class, $user);
    }
}
