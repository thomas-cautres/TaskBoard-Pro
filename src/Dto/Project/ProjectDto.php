<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectType;
use App\Entity\Project;
use App\Entity\ProjectColumn;

final class ProjectDto extends AbstractProjectDto
{
    public function __construct(
        private string $uuid,
        private string $name,
        private ?string $description = null,
        private ProjectType $type,
        private ?\DateTimeImmutable $startDate = null,
        private ?\DateTimeImmutable $endDate = null,
        private \DateTimeImmutable $createdAt,
        private array $columns = [],
        protected string $createdByEmail,
    ) {
    }

    public static function fromEntity(Project $project): self
    {
        return new self(
            uuid: $project->getUuid()->toRfc4122(),
            name: $project->getName(),
            description: $project->getDescription(),
            type: $project->getType(),
            startDate: $project->getStartDate(),
            endDate: $project->getEndDate(),
            createdAt: $project->getCreatedAt(),
            columns: array_map(fn (ProjectColumn $projectColumn) => ProjectColumnDto::fromEntity($projectColumn), $project->getColumnsSortedByPosition()->toArray()),
            createdByEmail: $project->getCreatedBy()->getEmail()
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

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getCreatedByEmail(): string
    {
        return $this->createdByEmail;
    }
}
