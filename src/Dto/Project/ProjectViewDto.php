<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectType;
use App\Entity\Project;
use Symfony\Component\Uid\Uuid;

final readonly class ProjectViewDto
{
    /**
     * @param array<mixed> $columns
     */
    public function __construct(
        public int $id,
        public Uuid $uuid,
        public string $name,
        public ?string $description,
        public ProjectType $type,
        public ?\DateTimeImmutable $startDate,
        public ?\DateTimeImmutable $endDate,
        public \DateTimeImmutable $createdAt,
        public string $createdByEmail,
        public int $columnsCount,
        public array $columns,
    ) {
    }

    public static function fromEntity(Project $project): self
    {
        return new self(
            id: $project->getId(),
            uuid: $project->getUuid(),
            name: $project->getName(),
            description: $project->getDescription(),
            type: $project->getType(),
            startDate: $project->getStartDate(),
            endDate: $project->getEndDate(),
            createdAt: $project->getCreatedAt(),
            createdByEmail: $project->getCreatedBy()->getEmail(),
            columnsCount: $project->getColumns()->count(),
            columns: $project->getColumns()->toArray()
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

    public function isActive(): bool
    {
        $now = new \DateTimeImmutable();

        if (null === $this->endDate) {
            return true;
        }

        return $this->endDate >= $now;
    }
}
