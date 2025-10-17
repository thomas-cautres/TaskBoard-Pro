<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use CoopTilleuls\UrlSignerBundle\UrlSigner\UrlSignerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationTest extends WebTestCase
{
    public function testIsSuccessful(): void
    {
        $client = static::createClient();

        $client->request('GET', '/registration');
        $this->assertResponseIsSuccessful();
        $content = $client->getResponse()->getContent();

        $this->assertStringContainsString('Register', $content);
        $this->assertStringContainsString('Join TaskBoard Pro for free', $content);
        $this->assertStringContainsString('Email', $content);
        $this->assertStringContainsString('Password', $content);
        $this->assertStringContainsString('Confirm password', $content);
        $this->assertStringContainsString('Sign up', $content);
        $this->assertStringContainsString('Already have an account?', $content);
        $this->assertStringContainsString('Log in', $content);
        $this->assertStringContainsString('Back to home', $content);

        $client->submitForm('signup-btn', [
            'registration[email]' => 'test@test.com',
            'registration[password][first]' => 'testtest123',
            'registration[password][second]' => 'testtest123',
        ]);
        /** @var UrlSignerInterface $urlSigner */
        $urlSigner = static::getContainer()->get(UrlSignerInterface::class);

        $this->assertResponseRedirects($urlSigner->sign('/confirm/test@test.com', 3600));

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
        $this->assertSelectorTextContains('.invalid-feedback', 'The password fields must match.');
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
        $this->assertSelectorTextContains('.invalid-feedback', 'This value is too short. It should have 8 characters or more.');
    }

    public function testRegistrationWithExistingEmail(): void
    {
        $client = static::createClient();
        $container = static::$kernel->getContainer();

        $entityManager = $container->get('doctrine')->getManager();
        $existingUser = new User();
        $existingUser->setEmail('existing@test.com')
            ->setPassword('hashedpassword')
            ->setConfirmationCode('1234');

        $entityManager->persist($existingUser);
        $entityManager->flush();

        $client->request('GET', '/registration');
        $client->submitForm('signup-btn', [
            'registration[email]' => 'existing@test.com',
            'registration[password][first]' => 'testtest123',
            'registration[password][second]' => 'testtest123',
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorTextContains('.invalid-feedback', 'This email is already registered.');
    }
}
