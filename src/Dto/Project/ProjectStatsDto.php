<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectColumnName;
use App\Entity\Project;
use App\Entity\Task;

final readonly class ProjectStatsDto
{
    public function __construct(
        private int $totalTasks,
        private int $inProgressTasks,
        private int $doneTasks,
    ) {
    }

    public static function fromEntity(Project $project): self
    {
        $totalTasks = $inProgressTasks = $doneTasks = 0;

        foreach ($project->getColumns() as $column) {
            $tasks = $column->getTasks();
            $inProgressTasks = $tasks->filter(fn (Task $task) => ProjectColumnName::InProgress->value === $task->getProjectColumn()?->getName())->count();
            $doneTasks = $tasks->filter(fn (Task $task) => ProjectColumnName::Done->value === $task->getProjectColumn()?->getName())->count();
        }

        return new self(
            totalTasks: $totalTasks,
            inProgressTasks: $inProgressTasks,
            doneTasks: $doneTasks,
        );
    }

    public function getTotalTasks(): int
    {
        return $this->totalTasks;
    }

    public function getInProgressTasks(): int
    {
        return $this->inProgressTasks;
    }

    public function getDoneTasks(): int
    {
        return $this->doneTasks;
    }
}
