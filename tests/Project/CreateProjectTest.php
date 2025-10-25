<?php

declare(strict_types=1);

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
            'Create a new project',
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
        $this->assertEquals('Project name', $project->getName());
        $this->assertEquals('Project description', $project->getDescription());
        $this->assertEquals('scrum', $project->getType()->value);
        $this->assertEquals('2025-01-01', $project->getStartDate()->format('Y-m-d'));
        $this->assertEquals('2025-02-01', $project->getEndDate()->format('Y-m-d'));
        $this->assertEquals('user-confirmed@domain.com', $project->getCreatedBy()->getEmail());

        $this->assertCount(5, $project->getColumns());
        $this->assertEquals('Backlog', $project->getColumns()->get(0)->getName());
        $this->assertEquals('To Do', $project->getColumns()->get(1)->getName());
        $this->assertEquals('In Progress', $project->getColumns()->get(2)->getName());
        $this->assertEquals('Review', $project->getColumns()->get(3)->getName());
        $this->assertEquals('Done', $project->getColumns()->get(4)->getName());

        $this->assertResponseRedirects(sprintf('/app/project/%s', $project->getUuid()));
    }

    public function testSubmitKanbanProject()
    {
        $this->client->request('GET', '/app/project/create');
        $this->client->submitForm('submit-btn', [
            'create_project[name]' => 'Project name',
            'create_project[description]' => 'Project description',
            'create_project[type]' => 'kanban',
            'create_project[startDate]' => '2025-01-01',
            'create_project[endDate]' => '2025-02-01',
        ]);

        $project = $this->projectRepository->findOneBy(['name' => 'Project name']);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals('Project name', $project->getName());
        $this->assertEquals('Project description', $project->getDescription());
        $this->assertEquals('kanban', $project->getType()->value);
        $this->assertEquals('2025-01-01', $project->getStartDate()->format('Y-m-d'));
        $this->assertEquals('2025-02-01', $project->getEndDate()->format('Y-m-d'));
        $this->assertEquals('user-confirmed@domain.com', $project->getCreatedBy()->getEmail());

        $this->assertCount(3, $project->getColumns());
        $this->assertEquals('To Do', $project->getColumns()->get(0)->getName());
        $this->assertEquals('In Progress', $project->getColumns()->get(1)->getName());
        $this->assertEquals('Done', $project->getColumns()->get(2)->getName());

        $this->assertResponseRedirects(sprintf('/app/project/%s', $project->getUuid()));
    }

    public function testSubmitBasicProject()
    {
        $this->client->request('GET', '/app/project/create');
        $this->client->submitForm('submit-btn', [
            'create_project[name]' => 'Project name',
            'create_project[description]' => 'Project description',
            'create_project[type]' => 'basic',
            'create_project[startDate]' => '2025-01-01',
            'create_project[endDate]' => '2025-02-01',
        ]);

        $project = $this->projectRepository->findOneBy(['name' => 'Project name']);

        $this->assertInstanceOf(Project::class, $project);
        $this->assertEquals('Project name', $project->getName());
        $this->assertEquals('Project description', $project->getDescription());
        $this->assertEquals('basic', $project->getType()->value);
        $this->assertEquals('2025-01-01', $project->getStartDate()->format('Y-m-d'));
        $this->assertEquals('2025-02-01', $project->getEndDate()->format('Y-m-d'));
        $this->assertEquals('user-confirmed@domain.com', $project->getCreatedBy()->getEmail());

        $this->assertCount(2, $project->getColumns());
        $this->assertEquals('Open', $project->getColumns()->get(0)->getName());
        $this->assertEquals('Closed', $project->getColumns()->get(1)->getName());

        $this->assertResponseRedirects(sprintf('/app/project/%s', $project->getUuid()));
    }
}
