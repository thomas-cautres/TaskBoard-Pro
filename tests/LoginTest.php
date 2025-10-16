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
        $this->assertStringContainsString('Accédez à votre espace', $content);
        $this->assertStringContainsString('Email', $content);
        $this->assertStringContainsString('Mot de passe', $content);
        $this->assertStringContainsString('Se souvenir de moi', $content);
        $this->assertStringContainsString('Se connecter', $content);
        $this->assertStringContainsString('Mot de passe oublié ?', $content);
        $this->assertStringContainsString('Pas encore de compte ?', $content);
        $this->assertStringContainsString('S&#039;inscrire', $content);
        $this->assertStringContainsString('Retour à l&#039;accueil', $content);

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
