<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use CoopTilleuls\UrlSignerBundle\UrlSigner\UrlSignerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConfirmationTest extends WebTestCase
{
    public function testIsSuccessful(): void
    {
        $client = static::createClient();
        /** @var UrlSignerInterface $urlSigner */
        $urlSigner = static::getContainer()->get(UrlSignerInterface::class);
        $url = $urlSigner->sign('/confirm/user-unconfirmed@domain.com', 3600);

        $client->request('GET', $url);
        $this->assertResponseIsSuccessful();

        $content = $client->getResponse()->getContent();

        $this->assertStringContainsString('Verify your account', $content);
        $this->assertStringContainsString('Enter verification code', $content);
        $this->assertStringContainsString("We&#039;ve sent a 6-digit verification code to your email address. Please enter it below to verify your account.", $content);
        $this->assertStringContainsString('Verify account', $content);
        $this->assertStringContainsString('Back to login', $content);

        $client->submitForm('confirm-btn', [
            'confirm[confirmationCode]' => '1234',
        ]);

        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Your registration was successfully confirmed.');

        $container = static::$kernel->getContainer();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine')->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => 'user-unconfirmed@domain.com']);

        $this->assertEquals(true, $user->isConfirmed());
    }

    public function testWithInvalidCode(): void
    {
        $client = static::createClient();

        /** @var UrlSignerInterface $urlSigner */
        $urlSigner = static::getContainer()->get(UrlSignerInterface::class);
        $url = $urlSigner->sign('/confirm/user-unconfirmed@domain.com', 3600);

        $client->request('GET', $url);
        $client->submitForm('confirm-btn', [
            'confirm[confirmationCode]' => '1233',
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorTextContains('.invalid-feedback', "This code is not valid.");
    }

    public function testWithInvalidSignature(): void
    {
        $client = static::createClient();

        /** @var UrlSignerInterface $urlSigner */
        $urlSigner = static::getContainer()->get(UrlSignerInterface::class);
        $url = $urlSigner->sign('/confirm/user-unconfirmed@domain.com', 3600);

        $tamperedUrl = $url.'&tampered=true';

        $client->request('GET', $tamperedUrl);

        $this->assertResponseStatusCodeSame(403);
    }

    public function testWithUnsignedUrl(): void
    {
        $client = static::createClient();

        $client->request('GET', '/confirm/user-unconfirmed@domain.com');

        $this->assertResponseStatusCodeSame(403);
    }

    public function testWithNonExistentUser(): void
    {
        $client = static::createClient();

        /** @var UrlSignerInterface $urlSigner */
        $urlSigner = static::getContainer()->get(UrlSignerInterface::class);
        $url = $urlSigner->sign('/confirm/nonexistent@domain.com', 3600);

        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame(404);
    }
}
