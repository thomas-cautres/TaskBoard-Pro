<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testLoginIsSuccessful(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $client->submitForm('login-btn', [
            '_username' => 'user-confirmed@domain.com',
            '_password' => 'test1234',
        ]);

        $this->assertResponseRedirects('/');
    }

    public function testPasswordIsInvalid(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $client->submitForm('login-btn', [
            '_username' => 'user-confirmed@domain.com',
            '_password' => 'test',
        ]);

        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', 'Identifiants invalides.');
    }

    public function testUserIsNotConfirmed(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $client->submitForm('login-btn', [
            '_username' => 'user-unconfirmed@domain.com',
            '_password' => 'test',
        ]);

        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', "Votre inscription n'est pas confirmée.");
    }
}
