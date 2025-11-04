<?php

declare(strict_types=1);

namespace App\Dto\Project;

use App\AppEnum\ProjectStatus;
use App\Entity\Project;
use App\ObjectMapper\UserTransformer;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: Project::class)]
abstract class AbstractProjectDto
{
    #[Map(target: 'createdByEmail', source: 'createdBy', transform: UserTransformer::class)]
    public string $createdByEmail = '';
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

    public function getCreatedByEmail(): string
    {
        return $this->createdByEmail;
    }

    public function setCreatedByEmail(string $createdByEmail): static
    {
        $this->createdByEmail = $createdByEmail;

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
