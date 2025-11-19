<?php

declare(strict_types=1);

namespace App\Dto\Request\Project;

use App\AppEnum\ProjectType;
use App\Dto\View\Project\AbstractProjectModel;
use App\Entity\Project;
use Symfony\Component\Uid\Uuid;

final class EditProjectFormData extends AbstractProjectModel
{
    public function __construct(
        protected string $createdByEmail,
        private string $name,
        private Uuid $uuid,
        private ProjectType $type,
        private ?string $description = null,
        private ?\DateTimeImmutable $startDate = null,
        private ?\DateTimeImmutable $endDate = null,
    ) {
    }

    public static function fromEntity(Project $project): self
    {
        return new self(
            createdByEmail: $project->getCreatedBy()->getEmail(),
            name: $project->getName(),
            uuid: $project->getUuid(),
            type: $project->getType(),
            description: $project->getDescription(),
            startDate: $project->getStartDate(),
            endDate: $project->getEndDate()
        );
    }

    public function getCreatedByEmail(): string
    {
        return $this->createdByEmail;
    }

    public function setCreatedByEmail(string $createdByEmail): EditProjectFormData
    {
        $this->createdByEmail = $createdByEmail;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): EditProjectFormData
    {
        $this->name = $name;

        return $this;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): EditProjectFormData
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getType(): ProjectType
    {
        return $this->type;
    }

    public function setType(ProjectType $type): EditProjectFormData
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): EditProjectFormData
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeImmutable $startDate): EditProjectFormData
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeImmutable $endDate): EditProjectFormData
    {
        $this->endDate = $endDate;

        return $this;
    }
}
