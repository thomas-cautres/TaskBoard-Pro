<?php

declare(strict_types=1);

namespace App\Dto\View\Project;

use App\AppEnum\ProjectStatus;

abstract class AbstractProjectModel
{
    protected string $createdByEmail;
    protected ProjectStatus $status = ProjectStatus::Active;

    public function getStatusAsString(): string
    {
        return $this->status->value;
    }

    public function setStatusAsString(string $status): static
    {
        $this->status = ProjectStatus::from($status);

        return $this;
    }

    public function getCreatedByEmail(): string
    {
        return $this->createdByEmail;
    }

    public function getStatus(): ProjectStatus
    {
        return $this->status;
    }
}
