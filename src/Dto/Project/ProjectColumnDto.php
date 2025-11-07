<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\Entity\ProjectColumn;

final class ProjectColumnDto
{
    public function __construct(
        private string $uuid,
        private string $name,
        private int $position,
        private array $tasks = [],
        private string $projectUuid,
    ) {
    }

    public static function fromEntity(ProjectColumn $projectColumn): self
    {
        return new self(
            uuid: $projectColumn->getUuid()->toRfc4122(),
            name: $projectColumn->getName(),
            position: $projectColumn->getPosition(),
            tasks: $projectColumn->getTasks()->toArray(),
            projectUuid: $projectColumn->getProject()->getUuid()->toRfc4122()
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

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }

    public function getProjectUuid(): string
    {
        return $this->projectUuid;
    }
}
