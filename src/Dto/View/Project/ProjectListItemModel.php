<?php

declare(strict_types=1);

namespace App\Dto\View\Project;

use App\AppEnum\ProjectStatus;
use App\Dto\Response\Project\ProjectStatsResponse;
use App\Entity\Project;

final class ProjectListItemModel extends AbstractProjectModel
{
    public function __construct(
        private string $uuid,
        private string $name,
        private string $type,
        protected string $createdByEmail,
        protected ProjectStatus $status,
        private ProjectStatsResponse $stats,
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
            stats: ProjectStatsResponse::fromEntity($project),
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

    public function getStats(): ProjectStatsResponse
    {
        return $this->stats;
    }
}
