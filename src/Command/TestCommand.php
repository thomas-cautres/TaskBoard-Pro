<?php

declare(strict_types=1);

namespace App\Command;

use App\Dto\Project\ProjectDto;
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
        $project = $this->projectRepository->find(1);

        $projectDto = $this->objectMapper->map($project, ProjectDto::class);

        // $project = $this->objectMapper->map($projectDto, $project);
        // dump($projectDto);
        return Command::SUCCESS;
    }
}
