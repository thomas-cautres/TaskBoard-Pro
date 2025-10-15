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

        $client->request('GET', '/registration');
        $this->assertResponseIsSuccessful();

        $client->submitForm('signup-btn', [
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

    public function testWithInvalidEmail(): void
    {
        $client = static::createClient();

        $client->request('GET', '/registration');
        $this->assertResponseIsSuccessful();

        $client->submitForm('signup-btn', [
            'registration[email]' => 'test',
            'registration[password][first]' => 'testtest123',
            'registration[password][second]' => 'testtest123',
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

    public function testWithMismatchedPasswords(): void
    {
        $client = static::createClient();

        $client->request('GET', '/registration');
        $this->assertResponseIsSuccessful();

        $client->submitForm('signup-btn', [
            'registration[email]' => 'test@test.com',
            'registration[password][first]' => 'testtest123',
            'registration[password][second]' => 'testtest',
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorTextContains('.invalid-feedback', 'Les mots de passe doivent correspondre.');
    }

    public function testWithWeakPassword(): void
    {
        $client = static::createClient();

        $client->request('GET', '/registration');
        $this->assertResponseIsSuccessful();

        $client->submitForm('signup-btn', [
            'registration[email]' => 'test@test.com',
            'registration[password][first]' => 'test',
            'registration[password][second]' => 'test',
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorTextContains('.invalid-feedback', 'Cette chaîne est trop courte. Elle doit avoir au minimum 8 caractères.');
    }

    public function testRegistrationWithExistingEmail(): void
    {
        $client = static::createClient();
        $container = static::$kernel->getContainer();

        $entityManager = $container->get('doctrine')->getManager();
        $existingUser = new User();
        $existingUser->setEmail('existing@test.com');
        $existingUser->setPassword('hashedpassword');
        $entityManager->persist($existingUser);
        $entityManager->flush();

        $client->request('GET', '/registration');
        $client->submitForm('signup-btn', [
            'registration[email]' => 'existing@test.com',
            'registration[password][first]' => 'testtest123',
            'registration[password][second]' => 'testtest123',
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorTextContains('.invalid-feedback', 'Cet email est déjà inscrit.');
    }
}
