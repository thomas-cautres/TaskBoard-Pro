<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectColumnName;
use App\AppEnum\ProjectStatus;
use App\AppEnum\ProjectType;
use App\Entity\Project;
use App\Entity\ProjectColumn;

final class ProjectDto extends AbstractProjectDto
{
    /**
     * @param array<int, ProjectColumnDto> $columns
     */
    public function __construct(
        private string $uuid,
        private string $name,
        private ProjectType $type,
        private \DateTimeImmutable $createdAt,
        private int $totalTasks,
        private int $inProgressTasks,
        private int $doneTasks,
        protected string $createdByEmail,
        protected ProjectStatus $status,
        private array $columns = [],
        private ?\DateTimeImmutable $startDate = null,
        private ?\DateTimeImmutable $endDate = null,
        private ?string $description = null,
    ) {
    }

    public static function fromEntity(Project $project): self
    {
        $totalTasks = 0;
        $inProgressTasks = 0;
        $doneTasks = 0;
        foreach ($project->getColumns() as $column) {
            foreach ($column->getTasks() as $task) {
                ++$totalTasks;

                match ($task->getProjectColumn()->getName()) {
                    ProjectColumnName::InProgress->value => $inProgressTasks++,
                    ProjectColumnName::Done->value, ProjectColumnName::Closed->value => $doneTasks++,
                    default => null,
                };
            }
        }

        return new self(
            uuid: $project->getUuid()->toRfc4122(),
            name: $project->getName(),
            type: $project->getType(),
            createdAt: $project->getCreatedAt(),
            totalTasks: $totalTasks,
            inProgressTasks: $inProgressTasks,
            doneTasks: $doneTasks,
            createdByEmail: $project->getCreatedBy()->getEmail(),
            status: $project->getStatus(),
            columns: array_map(fn (ProjectColumn $projectColumn) => ProjectColumnDto::fromEntity($projectColumn), $project->getColumnsSortedByPosition()->toArray()),
            startDate: $project->getStartDate(),
            endDate: $project->getEndDate(),
            description: $project->getDescription()
        );
    }

    public function getFormattedCreatedAt(string $format = 'Y-m-d'): string
    {
        return $this->createdAt->format($format);
    }

    public function getFormattedStartDate(string $format = 'Y-m-d'): ?string
    {
        return $this->startDate?->format($format);
    }

    public function getFormattedEndDate(string $format = 'Y-m-d'): ?string
    {
        return $this->endDate?->format($format);
    }

    public function getTypeLabel(): string
    {
        return $this->type->value;
    }

    public function hasDescription(): bool
    {
        return null !== $this->description && '' !== $this->description;
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

    public function getType(): ProjectType
    {
        return $this->type;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return array<int, ProjectColumnDto>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getCreatedByEmail(): string
    {
        return $this->createdByEmail;
    }

    public function getTotalTasks(): int
    {
        return $this->totalTasks;
    }

    public function getDoneTasks(): int
    {
        return $this->doneTasks;
    }

    public function getInProgressTasks(): int
    {
        return $this->inProgressTasks;
    }

    public function getTasksCompletedPercent(): int
    {
        if (0 === $this->getTotalTasks()) {
            return 0;
        }

        return (int) ((100 * $this->getDoneTasks()) / $this->getTotalTasks());
    }
}
