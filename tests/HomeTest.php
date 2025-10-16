<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeTest extends WebTestCase
{
    public function testHomePageContainsAllTranslations(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $content = $client->getResponse()->getContent();

        // Navigation translations
        $this->assertStringContainsString('Accueil', $content);
        $this->assertStringContainsString('Connexion', $content);
        $this->assertStringContainsString('S&#039;inscrire', $content);

        // Hero section translations
        $this->assertStringContainsString('Gérez vos projets avec efficacité', $content);
        $this->assertStringContainsString('TaskBoard Pro vous aide à organiser vos projets et tâches en toute simplicité', $content);
        $this->assertStringContainsString('Commencer gratuitement', $content);
        $this->assertStringContainsString('Se connecter', $content);

        // Features section translations
        $this->assertStringContainsString('Fonctionnalités principales', $content);
        $this->assertStringContainsString('Gestion de projets', $content);
        $this->assertStringContainsString('Créez et organisez vos projets facilement avec une interface intuitive.', $content);
        $this->assertStringContainsString('Suivi des tâches', $content);
        $this->assertStringContainsString('Assignez et suivez l&#039;avancement de chaque tâche en temps réel.', $content);
        $this->assertStringContainsString('Collaboration', $content);
        $this->assertStringContainsString('Travaillez en équipe et partagez vos projets avec vos collaborateurs.', $content);

        // Footer translation
        $this->assertStringContainsString('© 2025 TaskBoard Pro. Tous droits réservés.', $content);
    }

    public function testHomePageLinksWork(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // Test home link (navbar brand)
        $link = $crawler->filter('a.navbar-brand')->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('home');

        // Test login link in navigation
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('a.nav-link[href="/login"]')->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('login');

        // Test registration link in navigation
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('a.btn-primary[href="/registration"]')->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('registration');

        // Test "Start for Free" button
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('a.btn-light[href="/registration"]')->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('registration');

        // Test "Sign In" button
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('a.btn-outline-light[href="/login"]')->link();
        $client->click($link);
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('login');
    }
}
