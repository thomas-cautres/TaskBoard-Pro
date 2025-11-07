<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\Entity\Project;

final class ProjectListItemDto extends AbstractProjectDto
{
    public function __construct(
        private string $uuid,
        private string $name,
        private string $description,
        private string $type,
        protected string $createdByEmail,
    ) {
    }

    public static function fromEntity(Project $project): self
    {
        return new self(
            uuid: $project->getUuid()->toRfc4122(),
            name: $project->getName(),
            description: $project->getDescription(),
            type: $project->getType()->value,
            createdByEmail: $project->getCreatedBy()->getEmail(),
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

    public function getDescription(): string
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
}
