<?php

declare(strict_types=1);

namespace App\Dto\View\Project;

use App\AppEnum\ProjectStatus;
use App\AppEnum\ProjectType;
use App\Dto\Response\Project\ProjectStatsResponse;
use App\Entity\Project;
use App\Entity\ProjectColumn;

final class ProjectModel extends AbstractProjectModel
{
    /**
     * @param array<int, ProjectColumnModel> $columns
     */
    public function __construct(
        private string $uuid,
        private string $name,
        private ProjectType $type,
        private \DateTimeImmutable $createdAt,
        protected string $createdByEmail,
        protected ProjectStatus $status,
        private ProjectStatsResponse $stats,
        private array $columns = [],
        private ?\DateTimeImmutable $startDate = null,
        private ?\DateTimeImmutable $endDate = null,
        private ?string $description = null,
    ) {
    }

    public static function fromEntity(Project $project): self
    {
        return new self(
            uuid: $project->getUuid()->toRfc4122(),
            name: $project->getName(),
            type: $project->getType(),
            createdAt: $project->getCreatedAt(),
            createdByEmail: $project->getCreatedBy()->getEmail(),
            status: $project->getStatus(),
            stats: ProjectStatsResponse::fromEntity($project),
            columns: array_map(fn (ProjectColumn $projectColumn) => ProjectColumnModel::fromEntity($projectColumn), $project->getColumnsSortedByPosition()->toArray()),
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
     * @return array<int, ProjectColumnModel>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getCreatedByEmail(): string
    {
        return $this->createdByEmail;
    }

    public function getStats(): ProjectStatsResponse
    {
        return $this->stats;
    }
}
