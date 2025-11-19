<?php

declare(strict_types=1);

namespace App\Tests\Api\Project;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListProjectsTest extends WebTestCase
{
    private ?KernelBrowser $client = null;

    public function setUp(): void
    {
        $this->client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = $this->client->getContainer()->get(UserRepository::class);
        $this->client->loginUser($userRepository->findOneBy(['email' => 'user-confirmed@domain.com']), firewallContext: 'api');
    }

    public function testListProjects(): void
    {
        $this->client->request('GET', '/api/projects');

        $content = $this->client->getResponse()->getContent();

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
        $this->client->request('GET', '/api/projects?name=test');

        $content = $this->client->getResponse()->getContent();

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
        $this->client->request('GET', '/api/projects?type=INVALID');

        $content = $this->client->getResponse()->getContent();

        $response = json_decode($content, true);
        $this->assertResponseStatusCodeSame(422);
        $this->assertJson($content);

        $this->assertArrayHasKey('code', $response);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('details', $response);

        $this->assertSame('INVALID_PARAMETERS', $response['code']);
        $this->assertSame('Invalid query parameters', $response['message']);
    }
}
