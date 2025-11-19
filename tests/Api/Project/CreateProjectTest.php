<?php

declare(strict_types=1);

namespace App\Tests\Api\Project;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateProjectTest extends WebTestCase
{
    public function setUp(): void
    {
        $this->client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = $this->client->getContainer()->get(UserRepository::class);
        $this->client->loginUser($userRepository->findOneBy(['email' => 'user-confirmed@domain.com']));
    }

    public function testProjectIsSuccessfullyCreated(): void
    {
        $this->client->request('POST', '/api/project', [
            'name' => 'test',
            'type' => 'scrum',
            'start_date' => '2025-01-01',
            'end_date' => '2025-02-01',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    public function testTypeIsInvalid(): void
    {
        $this->client->request('POST', '/api/project', [
            'name' => 'test',
            'type' => 'INVALID',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testNameIsEmpty(): void
    {
        $this->client->request('POST', '/api/project', [
            'name' => '',
            'type' => 'scrum',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEndDateIsLessThanStartDate(): void
    {
        $this->client->request('POST', '/api/project', [
            'name' => 'test',
            'type' => 'scrum',
            'start_date' => '2025-01-01',
            'end_date' => '2024-01-01',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testEndDateIsLessThanStartDate(): void
    {
        $this->client->request('POST', '/api/project', [
            'name' => 'test',
            'type' => 'scrum',
            'start_date' => '2025-01-01',
            'end_date' => '2024-01-01',
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
