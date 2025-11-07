<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectType;
use App\Entity\Project;
use Symfony\Component\Uid\Uuid;

final class EditProjectDto extends AbstractProjectDto
{
    public function __construct(
        private ?string $name = null,
        private ?string $description = null,
        private ?ProjectType $type,
        private ?\DateTimeImmutable $startDate = null,
        private ?\DateTimeImmutable $endDate = null,
        private ?Uuid $uuid,
        protected string $createdByEmail,
    ) {
    }

    public static function fromEntity(Project $project): self
    {
        return new self(
            name: $project->getName(),
            description: $project->getDescription(),
            type: $project->getType(),
            startDate: $project->getStartDate(),
            endDate: $project->getEndDate(),
            uuid: $project->getUuid(),
            createdByEmail: $project->getCreatedBy()->getEmail()
        );
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): EditProjectDto
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): EditProjectDto
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?ProjectType
    {
        return $this->type;
    }

    public function setType(?ProjectType $type): EditProjectDto
    {
        $this->type = $type;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): EditProjectDto
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): EditProjectDto
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(?Uuid $uuid): EditProjectDto
    {
        $this->uuid = $uuid;

        return $this;
    }
}
