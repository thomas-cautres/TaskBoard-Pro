<?php

declare(strict_types=1);

namespace App\Command;

use App\AppEnum\ProjectStatus;
use App\Dto\Project\ProjectDto;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

#[AsCommand(name: 'test')]
class TestCommand
{
    public function __construct(private ProjectRepository $projectRepository, private ObjectMapperInterface $objectMapper)
    {
    }

    public function __invoke(): int
    {
        $project = new ProjectDto();
        $project->id = 10;
        $project->name = 'test';
        $project->status = ProjectStatus::Active;

        dump($this->objectMapper->map($project, Project::class));

        return Command::SUCCESS;
    }
}
