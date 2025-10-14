<?php

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
            '_username' => 'test@email.com',
            '_password' => 'test1234',
        ]);

        $this->assertResponseRedirects('/');
    }
}
