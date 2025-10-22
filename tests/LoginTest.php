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
        $content = $client->getResponse()->getContent();

        $this->assertStringContainsString('TaskBoard Pro', $content);
        $this->assertStringContainsString('Access your space', $content);
        $this->assertStringContainsString('Email', $content);
        $this->assertStringContainsString('Password', $content);
        $this->assertStringContainsString('Remember me', $content);
        $this->assertStringContainsString('Sign in', $content);
        $this->assertStringContainsString('Forgot password?', $content);
        $this->assertStringContainsString('Don&#039;t have an account yet?', $content);
        $this->assertStringContainsString('Sign up', $content);
        $this->assertStringContainsString('Back to home', $content);

        $client->submitForm('login-btn', [
            '_username' => 'user-confirmed@domain.com',
            '_password' => 'test1234',
        ]);

        $this->assertResponseRedirects('/dashboard');
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
        $this->assertSelectorTextContains('.alert-danger', 'Invalid credentials.');
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
        $this->assertSelectorTextContains('.alert-danger', 'Your registration is not confirmed.');
    }

    public function testPageLinksWork(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler->filter('a[href="/registration"]')->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('registration');

        $link = $crawler->filter('a[href="/"]')->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('home');
    }
}
