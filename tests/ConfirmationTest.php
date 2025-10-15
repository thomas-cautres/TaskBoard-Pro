<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConfirmationTest extends WebTestCase
{
    public function testIsSuccessful(): void
    {
        $client = static::createClient();

        $client->request('GET', '/confirm/user-unconfirmed@domain.com');
        $this->assertResponseIsSuccessful();

        $client->submitForm('confirm-btn', [
            'confirm[confirmationCode]' => '1234',
        ]);

        $this->assertResponseRedirects('/login');

        $container = static::$kernel->getContainer();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine')->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => 'user-unconfirmed@domain.com']);

        $this->assertEquals(true, $user->isConfirmed());
    }

    public function testWithMismatchedPasswords(): void
    {
        $client = static::createClient();

        $client->request('GET', '/confirm/user-unconfirmed@domain.com');

        $client->submitForm('confirm-btn', [
            'confirm[confirmationCode]' => '1233',
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorTextContains('.invalid-feedback', "Ce code n'est pas valide.");
    }
}
