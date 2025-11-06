<?php

declare(strict_types=1);

namespace App\Generator;

use App\Entity\Project;
use App\Entity\Task;
use App\Repository\TaskRepository;

final readonly class TaskCodeGenerator implements TaskGeneratorInterface
{
    private const string SEPARATOR = '-';
    private const int PREFIX_LENGTH = 3;

    public function __construct(
        private TaskRepository $taskRepository,
    ) {
    }

    public function generate(Task $task): string
    {
        $project = $task->getProjectColumn()->getProject();

        return $this->getPrefix($project).self::SEPARATOR.$this->getNextCounter($project);
    }

    private function getPrefix(Project $project): string
    {
        return strtoupper(
            substr(
                str_replace(self::SEPARATOR, '', $project->getName()),
                0,
                self::PREFIX_LENGTH
            )
        );
    }

    private function getNextCounter(Project $project): int
    {
        $lastProjectTask = $this->taskRepository->findLastForProject($project);

        $counter = 0;

        if ($lastProjectTask instanceof Task) {
            $codeExploded = explode(self::SEPARATOR, $lastProjectTask->getCode());
            $counter = (int) $codeExploded[1];
        }

        return $counter + 1;
    }
}
