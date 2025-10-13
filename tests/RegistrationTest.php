<?php

namespace App\Tests;

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
    }
}
