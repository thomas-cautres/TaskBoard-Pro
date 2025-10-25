<?php

namespace App\Tests\Project;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateProjectTest extends WebTestCase
{
    private UserRepository $userRepository;
    private ProjectRepository $projectRepository;
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->userRepository = static::getContainer()->get(UserRepository::class);
        $this->projectRepository = static::getContainer()->get(ProjectRepository::class);
        $loggedUser = $this->userRepository->findOneBy(['email' => 'user-confirmed@domain.com']);

        $this->client->loginUser($loggedUser);
    }

    public function testIsSuccessful()
    {
        $this->client->request('GET', '/app/project/create');

        $content = $this->client->getResponse()->getContent();

        $assertions = [
            'Projects',
            'Create a project',
            'Create a new project'
        ];

        foreach ($assertions as $assertion) {
            $this->assertStringContainsString($assertion, $content);
        }

        $this->assertResponseIsSuccessful();
    }

    public function testSubmitScrumProject()
    {
        $this->client->request('GET', '/app/project/create');
        $this->client->submitForm('submit-btn', [
            'create_project[name]' => 'Project name',
            'create_project[description]' => 'Project description',
            'create_project[type]' => 'scrum',
            'create_project[startDate]' => '2025-01-01',
            'create_project[endDate]' => '2025-02-01',
        ]);

        $project = $this->projectRepository->findOneBy(['name' => 'Project name']);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertResponseRedirects(sprintf('/app/project/%s', $project->getUuid()));
    }
}
