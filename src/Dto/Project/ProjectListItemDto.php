<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectStatus;
use App\Entity\Project;

final class ProjectListItemDto extends AbstractProjectDto
{
    public function __construct(
        private string $uuid,
        private string $name,
        private string $type,
        protected string $createdByEmail,
        protected ProjectStatus $status,
        private ProjectStatsDto $stats,
        private ?string $description = null,
    ) {
    }

    public static function fromEntity(Project $project): self
    {
        return new self(
            uuid: $project->getUuid()->toRfc4122(),
            name: $project->getName(),
            type: $project->getType()->value,
            createdByEmail: $project->getCreatedBy()->getEmail(),
            status: $project->getStatus(),
            stats: ProjectStatsDto::fromEntity($project),
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

    public function getStats(): ProjectStatsDto
    {
        return $this->stats;
    }
}
