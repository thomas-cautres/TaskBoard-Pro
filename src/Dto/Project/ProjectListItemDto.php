<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectColumnName;
use App\AppEnum\ProjectStatus;
use App\Entity\Project;
use App\Entity\Task;

final class ProjectListItemDto extends AbstractProjectDto
{
    public function __construct(
        private string $uuid,
        private string $name,
        private string $type,
        protected string $createdByEmail,
        protected ProjectStatus $status,
        private int $totalTasks,
        private int $inProgressTasks,
        private int $doneTasks,
        private ?string $description = null,
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
            uuid: $project->getUuid()->toRfc4122(),
            name: $project->getName(),
            type: $project->getType()->value,
            createdByEmail: $project->getCreatedBy()->getEmail(),
            status: $project->getStatus(),
            totalTasks: $totalTasks,
            inProgressTasks: $inProgressTasks,
            doneTasks: $doneTasks,
            description: $project->getDescription(),
        );
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCreatedByEmail(): string
    {
        return $this->createdByEmail;
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

    public function getTasksCompletedPercent(): int
    {
        if (0 === $this->getTotalTasks()) {
            return 0;
        }

        return (int) ((100 * $this->getDoneTasks()) / $this->getTotalTasks());
    }
}
