<?php

declare(strict_types=1);

namespace App\Dto\Project\Api;

use App\AppEnum\ProjectStatus;
use App\Dto\Project\AbstractProjectDto;
use App\Dto\Project\ProjectStatsDto;
use App\Entity\Project;

final class ProjectListItemDto extends AbstractProjectDto
{
    public function __construct(
        protected string $createdByEmail,
        protected ProjectStatus $status,
        private string $id,
        private string $name,
        private ?string $description = null,
        private string $type,
        private string $createdAt,
        private ProjectStatsDto $stats,
    ) {
    }

    public static function fromEntity(Project $project): self
    {
        return new self(
            createdByEmail: $project->getCreatedBy()->getEmail(),
            status: $project->getStatus(),
            id: $project->getUuid()->toRfc4122(),
            name: $project->getName(),
            description: $project->getDescription(),
            type: $project->getType()->value,
            createdAt: $project->getCreatedAt()->format('UTC'),
            stats: ProjectStatsDto::fromEntity($project)
        );
    }

    public function getStatus(): ProjectStatus
    {
        return $this->status;
    }

    public function getId(): string
    {
        return $this->id;
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

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getStats(): ProjectStatsDto
    {
        return $this->stats;
    }
}
