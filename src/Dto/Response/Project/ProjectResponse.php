<?php

declare(strict_types=1);

namespace App\Dto\Response\Project;

use App\AppEnum\ProjectStatus;
use App\Dto\View\Project\AbstractProjectModel;
use App\Entity\Project;

final class ProjectResponse extends AbstractProjectModel
{
    public function __construct(
        protected string $createdByEmail,
        protected ProjectStatus $status,
        private string $id,
        private string $name,
        private string $type,
        private string $createdAt,
        private ProjectStatsResponse $stats,
        private ?string $description = null,
    ) {
    }

    public static function fromEntity(Project $project): self
    {
        return new self(
            createdByEmail: $project->getCreatedBy()->getEmail(),
            status: $project->getStatus(),
            id: $project->getUuid()->toRfc4122(),
            name: $project->getName(),
            type: $project->getType()->value,
            createdAt: $project->getCreatedAt()->format('Y-m-d\TH:i:s\Z'),
            stats: ProjectStatsResponse::fromEntity($project),
            description: $project->getDescription()
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

    public function getStats(): ProjectStatsResponse
    {
        return $this->stats;
    }
}
