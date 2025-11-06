<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectStatus;
use App\Dto\UserDto;
use App\Entity\Project;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(target: Project::class, source: Project::class)]
abstract class AbstractProjectDto
{
    #[Map(if: false)]
    protected UserDto $createdBy;
    protected ProjectStatus $status = ProjectStatus::Active;

    public function getStatus(): ProjectStatus
    {
        return $this->status;
    }

    public function setStatus(ProjectStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedBy(): UserDto
    {
        return $this->createdBy;
    }

    public function setCreatedBy(UserDto $createdBy): AbstractProjectDto
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getStatusAsString(): string
    {
        return $this->status->value;
    }

    public function setStatusAsString(string $status): static
    {
        $this->status = ProjectStatus::from($status);

        return $this;
    }
}
