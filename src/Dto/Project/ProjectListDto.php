<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectType;
use App\Entity\Project;
use Symfony\Component\Uid\Uuid;

final readonly class ProjectListDto implements ProjectDtoInterface
{
    public function __construct(
        public Uuid $uuid,
        public string $name,
        public ?string $description,
        public ProjectType $type,
        public \DateTimeImmutable $createdAt,
    ) {
    }

    public static function fromEntity(Project $project): self
    {
        return new self(
            uuid: $project->getUuid(),
            name: $project->getName(),
            description: $project->getDescription(),
            type: $project->getType(),
            createdAt: $project->getCreatedAt(),
        );
    }

    public function getFormattedCreatedAt(string $format = 'Y-m-d'): string
    {
        return $this->createdAt->format($format);
    }

    public function getTypeLabel(): string
    {
        return $this->type->value;
    }

    public function hasDescription(): bool
    {
        return null !== $this->description && '' !== $this->description;
    }
}
