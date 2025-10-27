<?php

declare(strict_types=1);

namespace App\Tests\Project;

use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShowProjectTest extends WebTestCase
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

    public function testIsSuccessful(): void
    {
        $this->client->request('GET', '/app/project/019a2646-0166-70fc-80c2-0ddbc097a592');
        $this->assertResponseIsSuccessful();

        // Project name
        $this->assertSelectorTextContains('h1', 'Project Scrum');

        // Breadcrumb navigation
        $this->assertSelectorExists('.breadcrumb');
        $this->assertSelectorTextContains('.breadcrumb .breadcrumb-item.active', 'Project Scrum');

        // Project type badge
        $this->assertSelectorExists('.badge-type');
        $this->assertSelectorTextContains('.badge-type', 'SCRUM');

        // Project status badge
        $this->assertSelectorExists('.badge-status');
        $this->assertSelectorTextContains('.badge-status', 'INACTIVE');

        // Project description
        $this->assertSelectorExists('.project-header .text-muted');

        // Statistics cards
        $this->assertSelectorExists('.stat-card.primary');
        $this->assertSelectorTextContains('.stat-card.primary', 'Total Tasks');
        $this->assertSelectorExists('.stat-card.warning');
        $this->assertSelectorTextContains('.stat-card.warning', 'In Progress');
        $this->assertSelectorExists('.stat-card.success');
        $this->assertSelectorTextContains('.stat-card.success', 'Completed');
        $this->assertSelectorExists('.stat-card.info');
        $this->assertSelectorTextContains('.stat-card.info', 'Progress');

        // Kanban board
        $this->assertSelectorExists('.kanban-board');
        $this->assertSelectorExists('.kanban-column');

        // Action buttons
        $this->assertSelectorExists('a.btn.btn-outline-primary');
        $this->assertSelectorTextContains('a.btn.btn-outline-primary', 'Edit');
    }
}
