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
        $this->assertStringContainsString('Home', $content);
        $this->assertStringContainsString('Login', $content);
        $this->assertStringContainsString('Register', $content);

        // Hero section translations
        $this->assertStringContainsString('Manage your projects efficiently', $content);
        $this->assertStringContainsString('TaskBoard Pro helps you organize your projects and tasks with ease. Collaborate, plan and succeed together.', $content);
        $this->assertStringContainsString('Collaborate, plan and succeed together.', $content);
        $this->assertStringContainsString('Start for free', $content);
        $this->assertStringContainsString('Log in', $content);

        // Features section translations
        $this->assertStringContainsString('Key features', $content);
        $this->assertStringContainsString('Project management', $content);
        $this->assertStringContainsString('Create and organize your projects easily with an intuitive interface.', $content);
        $this->assertStringContainsString('Task tracking', $content);
        $this->assertStringContainsString('Assign and track the progress of each task in real time.', $content);
        $this->assertStringContainsString('Collaboration', $content);
        $this->assertStringContainsString('Work as a team and share your projects with your collaborators.', $content);

        // Footer translation
        $this->assertStringContainsString('© 2025 TaskBoard Pro. All rights reserved.', $content);
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
