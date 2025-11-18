<?php

declare(strict_types=1);

namespace App\Tests\Api\Project;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListProjectsTest extends WebTestCase
{
    public function testListProjects(): void
    {
        $client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);

        $client->loginUser($userRepository->findOneBy(['email' => 'user-confirmed@domain.com']), firewallContext: 'api');
        $client->request('GET', '/api/projects');

        $content = $client->getResponse()->getContent();

        $response = json_decode($content, true);
        $this->assertResponseIsSuccessful();
        $this->assertJson($content);

        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('meta', $response);
        $this->assertArrayHasKey('filters', $response);

        $this->assertCount(12, $response['data']);
    }

    public function testListProjectsWithFilters(): void
    {
        $client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);

        $client->loginUser($userRepository->findOneBy(['email' => 'user-confirmed@domain.com']), firewallContext: 'api');
        $client->request('GET', '/api/projects?name=test');

        $content = $client->getResponse()->getContent();

        $response = json_decode($content, true);
        $this->assertResponseIsSuccessful();
        $this->assertJson($content);

        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('meta', $response);
        $this->assertArrayHasKey('filters', $response);

        $this->assertCount(1, $response['data']);

        $this->assertSame('test', $response['filters']['name']);
    }

    public function testListProjectsWithTypeInvalid(): void
    {
        $client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);

        $client->loginUser($userRepository->findOneBy(['email' => 'user-confirmed@domain.com']), firewallContext: 'api');
        $client->request('GET', '/api/projects?type=INVALID');

        $content = $client->getResponse()->getContent();

        $response = json_decode($content, true);
        $this->assertResponseStatusCodeSame(400);
        $this->assertJson($content);

        $this->assertArrayHasKey('code', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('details', $response);

        $this->assertSame('INVALID_PARAMETERS', $response['code']);
        $this->assertSame('Invalid query parameters', $response['message']);
    }
}
